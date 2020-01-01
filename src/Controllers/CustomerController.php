<?php

namespace CarRental\Controllers;

use CarRental\Models\CustomerModel;

class CustomerController extends AbstractController
{
  public function get()
  {
    $customerModel = new CustomerModel($this->db);
    $customers = $customerModel->getCustomers();

    return $this->render("Customers.html.twig", [
      "route" => "customers",
      "customers" => $customers
    ]);
  }
}
