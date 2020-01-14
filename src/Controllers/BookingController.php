<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\VehicleModel;
use CarRental\Models\CustomerModel;
use CarRental\Models\BookingModel;

class BookingController extends AbstractController
{
  public function newRental()
  {
    $vehicleModel = new VehicleModel($this->db);
    $customerModel = new CustomerModel($this->db);
    $bookingModel = new BookingModel($this->db);

    $vehicles = $vehicleModel->getVehicles(true);

    foreach ($vehicles as $key => $vehicle) {
      $vehicles[$key]['editable'] = !$bookingModel->isBookingActive($vehicle['id'], 'vehicle');
    }

    return $this->render("RentVehicle.html.twig", [
      "route" => "rent",
      "success" => null,
      "vehicles" => $vehicles,
      "customers" => $customerModel->getCustomers()
    ]);
  }

  public function newReturn()
  {
    $vehicleModel = new VehicleModel($this->db);

    return $this->render("ReturnVehicle.html.twig", [
      "route" => "return",
      "success" => null,
      "vehicles" => $vehicleModel->getVehicles(false)
    ]);
  }

  public function addBooking()
  {
    $data = $this->request->getData();
    if (empty($data)) throw new HTTPException("No POST data was provided", 500);

    $bookingModel = new BookingModel($this->db);
    $vehicleModel = new VehicleModel($this->db);
    $customerModel = new CustomerModel($this->db);
    $created = $bookingModel->addBooking($data["customerId"], $data["vehicleId"]);

    return $this->render("RentVehicle.html.twig", [
      "route" => "vehicles",
      "success" => $created ? true : false,
      "responseMessage" => $created ? "Rented vehicle " . $data["vehicleId"] : "Could not rent vehicle " . $data["vehicleId"],
      "vehicles" => $vehicleModel->getVehicles(true),
      "customers" => $customerModel->getCustomers()
    ]);
  }

  public function addReturn()
  {
    $data = $this->request->getData();
    if (empty($data)) throw new HTTPException("No POST data was provided", 500);

    $bookingModel = new BookingModel($this->db);
    $vehicleModel = new VehicleModel($this->db);
    $returned = $bookingModel->addReturn($data["vehicleId"]);

    return $this->render("ReturnVehicle.html.twig", [
      "route" => "vehicles",
      "success" => $returned ? true : false,
      "responseMessage" => $returned ? "Returned vehicle " . $data["vehicleId"] : "Could not return vehicle " . $data["vehicleId"],
      "vehicles" => $vehicleModel->getVehicles(false)
    ]);
  }
}
