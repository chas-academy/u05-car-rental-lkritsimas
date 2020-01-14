<?php

namespace CarRental\Controllers;

use CarRental\Exceptions\HTTPException;
use CarRental\Models\CustomerModel;
use CarRental\Models\BookingModel;

class CustomerController extends AbstractController
{
  public function newCustomer()
  {
    return $this->render("NewCustomer.html.twig", [
      "route" => "customers",
      "success" => null
    ]);
  }

  public function edit($data)
  {
    $customerModel = new CustomerModel($this->db);
    $customerUpdated = null;

    // Update customer if request method is POST
    if ($this->request->getMethod() === "POST") {
      $data = $this->request->getData();

      if (empty($data))
        throw new HTTPException("No POST data was provided", 500);
      else
        $customerUpdated = $customerModel->updateCustomer($data["id"], $data["firstname"], $data["surname"], $data["address"], $data["postcode"], $data["city"], $data["phone"]);
    }

    $customer = $customerModel->getCustomer($data["id"]);

    return $this->render("EditCustomer.html.twig", [
      "route" => "customers",
      "success" => $customerUpdated,
      "customer" => $customer
    ]);
  }

  public function get()
  {
    $customerModel = new CustomerModel($this->db);
    $bookingModel = new BookingModel($this->db);
    $customers = $customerModel->getCustomers();

    foreach ($customers as $key => $customer) {
      $customers[$key]['editable'] = $bookingModel->isBookingActive($customer['id']);
    }

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
    $created = $customerModel->addCustomer($data["id"], $data["firstname"], $data["surname"], $data["address"], $data["postcode"], $data["city"], $data["phone"]);

    return $this->render("NewCustomer.html.twig", [
      "route" => "customers",
      "success" => $created ? true : false,
      "responseMessage" => $created ? "Successfully created user " . $data["id"] : "Could not create user " . $data["id"],
      "customerData" => $data
    ]);
  }

  public function update()
  {
    $data = $this->request->getData();
    if (empty($data)) throw new HTTPException("No POST data was provided", 500);

    $customerModel = new customerModel($this->db);
    $customer = $customerModel->updateCustomer($data["id"], $data["firstname"], $data["surname"], $data["address"], $data["postcode"], $data["city"], $data["phone"]);

    return $this->render("EditCustomer.html.twig", [
      "route" => "customers",
      "success" => null,
      "customer" => $customer
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
