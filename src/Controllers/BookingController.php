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

        return $this->render("RentVehicle.html.twig", [
            "route" => "rent",
            "success" => null,
            "vehicles" => $vehicleModel->getVehicles(true),
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
        $bookingId = $bookingModel->addBooking($data["customerId"], $data["vehicleId"]);

        return $this->render("RentVehicle.html.twig", [
            "route" => "vehicles",
            "success" => $bookingId ? true : false,
            "responseMessage" => $bookingId ? "Created vehicle $bookingId" : "Could not create vehicle $bookingId",
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
        $bookingId = $bookingModel->addReturn($data["vehicleId"]);

        return $this->render("ReturnVehicle.html.twig", [
            "route" => "vehicles",
            "success" => $bookingId ? true : false,
            "responseMessage" => $bookingId ? "Returned vehicle " . $data["vehicleId"] . " with booking ID $bookingId" : "Could not create vehicle $bookingId",
            "vehicles" => $vehicleModel->getVehicles(false)
        ]);
    }
}
