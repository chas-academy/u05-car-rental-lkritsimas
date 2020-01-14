<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\VehicleModel;
use CarRental\Models\BookingModel;

class VehicleController extends AbstractController
{
  // Render the new vehicle view
  public function newVehicle()
  {
    $vehicleModel = new VehicleModel($this->db);
    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();

    return $this->render("NewVehicle.html.twig", [
      "route" => "vehicles",
      "makes" => $makes,
      "colors" => $colors,
      "success" => null
    ]);
  }

  // Get all vehicles
  public function get()
  {
    $vehicleModel = new VehicleModel($this->db);
    $bookingModel = new BookingModel($this->db);
    $vehicles = $vehicleModel->getVehicles();

    foreach ($vehicles as $key => $vehicle) {
      $vehicles[$key]['editable'] = $bookingModel->isBookingActive($vehicle['id'], 'vehicle');
    }

    return $this->render("Vehicles.html.twig", [
      "route" => "vehicles",
      "vehicles" => $vehicles
    ]);
  }

  // Add new vehicle
  public function add()
  {
    $vehicleModel = new VehicleModel($this->db);
    $created = null;

    if ($this->request->getMethod() === "POST") {
      $data = $this->request->getData();

      if (empty($data))
        throw new HTTPException("No POST data was provided", 500);
      else
        $created = $vehicleModel->addVehicle(strtoupper($data["id"]), $data["make"], $data["color"], $data["year"], $data["price"]);
    }

    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();

    return $this->render("NewVehicle.html.twig", [
      "route" => "vehicles",
      "makes" => $makes,
      "colors" => $colors,
      "success" => $created,
      "responseMessage" => $created ? "Successfully created vehicle" : "Could not create vehicle"
    ]);
  }

  // Edit vehicle
  public function edit($data)
  {
    $vehicleModel = new VehicleModel($this->db);
    $vehicleUpdated = null;

    // Update vehicle if request method is POST
    if ($this->request->getMethod() === "POST") {
      $data = $this->request->getData();

      if (empty($data))
        throw new HTTPException("No POST data was provided", 500);
      else
        $vehicleUpdated = $vehicleModel->updateVehicle(strtoupper($data["id"]), $data["make"], $data["color"], $data["year"], $data["price"]);
    }

    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();
    $vehicle = $vehicleModel->getVehicle(strtoupper($data["id"]));

    return $this->render("EditVehicle.html.twig", [
      "route" => "vehicles",
      "success" => $vehicleUpdated,
      "makes" => $makes,
      "colors" => $colors,
      "vehicle" => $vehicle
    ]);
  }

  // Remove vehicle
  public function remove()
  {
    $data = $this->request->getData();

    $vehicleModel = new VehicleModel($this->db);
    $vehicleRemoved = $vehicleModel->removeVehicle(strtoupper($data["id"]));

    return json_encode(["success" => $vehicleRemoved]);
  }
}
