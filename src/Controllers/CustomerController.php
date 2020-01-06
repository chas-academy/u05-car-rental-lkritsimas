<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\CustomerModel;

class CustomerController extends AbstractController
{
  public function newCustomer()
  {
    $vehicleModel = new CustomerModel($this->db);

    return $this->render("NewCustomer.html.twig", [
      "route" => "customers",
      "success" => null
    ]);
  }

  public function get()
  {
    $customerModel = new CustomerModel($this->db);
    $customers = $customerModel->getCustomers();

    return $this->render("Customers.html.twig", [
      "route" => "customers",
      "customers" => $customers
    ]);
  }

  public function add()
  {
    $data = $this->request->getData();

    var_dump($data);
    if (empty($data)) throw new HTTPException("No POST data was provided", 500);

    $customerModel = new CustomerModel($this->db);
    $customerId = $customerModel->addCustomer($data["id"], $data["firstname"], $data["surname"], $data["address"], $data["postcode"], $data["city"], $data["phone"]);
    $customer = [
      "id" => $customerId,
      "firstname" => $data["firstname"],
      "surname" => $data["surname"],
      "address" => $data["address"],
      "postcode" => $data["postcode"],
      "city" => $data["city"],
      "phone" => $data["phone"]
    ];

    return $this->render("NewCustomer.html.twig", [
      "route" => "customers",
      "success" => $customerId ? true : false,
      "responseMessage" => $customerId ? "Successfully created user $customerId" : "Could not create user $customerId",
      "customerData" => $customer
    ]);
  }

  public function remove()
  {
    $data = $this->request->getData();

    $customerModel = new customerModel($this->db);
    $customerRemoved = $customerModel->removeCustomer($data["id"]);

    return json_encode(["success" => $customerRemoved]);
  }
}
