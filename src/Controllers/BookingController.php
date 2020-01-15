<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\VehicleModel;
use CarRental\Models\CustomerModel;
use CarRental\Models\BookingModel;

class BookingController extends AbstractController
{
  public function rent()
  {
    $bookingModel = new BookingModel($this->db);
    $vehicleModel = new VehicleModel($this->db);
    $customerModel = new CustomerModel($this->db);
    $created = null;
    $response = null;

    if ($this->request->getMethod() === "POST") {
      $data = $this->request->getData();

      if (empty($data))
        throw new HTTPException("No POST data was provided", 500);
      else {
        $created = $bookingModel->addBooking($data["customerId"], $data["vehicleId"]);
        $response = $created ? "Rented vehicle " . $data["vehicleId"] : "Could not rent vehicle " . $data["vehicleId"];
      }
    }

    $vehicles = $vehicleModel->getVehicles();
    foreach ($vehicles as $key => $vehicle) {
      $vehicles[$key]['editable'] = !$bookingModel->isBookingActive($vehicle['id'], 'vehicle');
    }

    return $this->render("RentVehicle.html.twig", [
      "route" => "rent",
      "success" => $created,
      "responseMessage" => $response,
      "vehicles" => $vehicles,
      "customers" => $customerModel->getCustomers()
    ]);
  }

  public function return()
  {
    $bookingModel = new BookingModel($this->db);
    $vehicleModel = new VehicleModel($this->db);
    $returned = null;
    $response = null;

    if ($this->request->getMethod() === "POST") {
      $data = $this->request->getData();

      if (empty($data))
        throw new HTTPException("No POST data was provided", 500);
      else {
        $returned = $bookingModel->addReturn($data["vehicleId"]);
        $response = $returned ? "Returned vehicle " . $data["vehicleId"] : "Could not return vehicle " . $data["vehicleId"];
      }
    }

    $vehicles = $vehicleModel->getVehicles();
    foreach ($vehicles as $key => $vehicle) {
      $vehicles[$key]['editable'] = !$bookingModel->isBookingActive($vehicle['id'], 'vehicle');
    }

    return $this->render("ReturnVehicle.html.twig", [
      "route" => "return",
      "success" => $returned,
      "responseMessage" => $response,
      "vehicles" => $vehicles
    ]);
  }
}
