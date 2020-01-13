<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\VehicleModel;
use CarRental\Models\BookingModel;

class VehicleController extends AbstractController
{
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

  public function get()
  {
    $vehicleModel = new VehicleModel($this->db);
    $vehicles = $vehicleModel->getVehicles();

    return $this->render("Vehicles.html.twig", [
      "route" => "vehicles",
      "vehicles" => $vehicles
    ]);
  }

  public function add()
  {
    $data = $this->request->getData();
    if (empty($data)) throw new HTTPException("No POST data was provided", 500);

    $vehicleModel = new VehicleModel($this->db);
    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();
    $vehicleId = $vehicleModel->addVehicle(strtoupper($data["id"]), $data["make"], $data["color"], $data["year"], $data["price"]);
    $vehicle = [
      "id" => $vehicleId,
      "make" => $data["make"],
      "color" => $data["color"],
      "year" => $data["year"],
      "price" => $data["price"]
    ];

    return $this->render("NewVehicle.html.twig", [
      "route" => "vehicles",
      "makes" => $makes,
      "colors" => $colors,
      "success" => $vehicleId ? true : false,
      "responseMessage" => $vehicleId ? "Successfully created vehicle $vehicleId" : "Could not create vehicle $vehicleId",
      "vehicleData" => $vehicle
    ]);
  }

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

  public function remove()
  {
    $data = $this->request->getData();

    $vehicleModel = new VehicleModel($this->db);
    $vehicleRemoved = $vehicleModel->removeVehicle(strtoupper($data["id"]));

    return json_encode(["success" => $vehicleRemoved]);
  }
}
