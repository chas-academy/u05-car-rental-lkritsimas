<?php

namespace CarRental\Controllers;

use CarRental\Models\VehicleModel;
use CarRental\Models\BookingModel;

class VehicleController extends AbstractController
{
  public function get()
  {
    $vehicleModel = new vehicleModel($this->db);
    $vehicles = $vehicleModel->getVehicles();

    return $this->render("Vehicles.html.twig", [
      "route" => "vehicles",
      "vehicles" => $vehicles
    ]);
  }

  public function newVehicle()
  {
    $vehicleModel = new vehicleModel($this->db);
    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();

    return $this->render("NewVehicle.html.twig", [
      "route" => "vehicles",
      "makes" => $makes,
      "colors" => $colors
    ]);
  }

  public function add()
  {
    $data = $this->request->getData();

    $vehicleModel = new VehicleModel($this->db);
    $makes = $vehicleModel->getMakes();
    $colors = $vehicleModel->getColors();
    $vehicleId = $vehicleModel->addVehicle($data["id"], $data["make"], $data["color"], $data["year"], $data["price"]);
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
      "responseMessage" => $vehicleId,
      "vehicleData" => $vehicle
    ]);
  }

  public function remove()
  {
    $data = $this->request->getData();

    $vehicleModel = new VehicleModel($this->db);
    $vehicleRemoved = $vehicleModel->removeVehicle($data["id"]);

    return json_encode(["success" => $vehicleRemoved]);
  }
}
