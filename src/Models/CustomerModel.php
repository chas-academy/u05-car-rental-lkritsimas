<?php

namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class CustomerModel extends AbstractModel
{
  public function getCustomers()
  {
    $result = [];
    $query = "SELECT * FROM customers ORDER BY created_at";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute();
      $result = $statement->fetchAll();

      // Render error page
    } catch (DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }
  // Get data from a customer by customer ID
  public function getCustomer($id)
  {
    $result = [];
    $query = "SELECT * FROM customers WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute([":id" => $id]);
      $result = $statement->fetch();

      // Render error page
    } catch (DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }


  public function addCustomer($id, $firstname, $surname, $address, $postcode, $city, $phoneNumber)
  {
    $result = [];
    $query = "INSERT INTO customers (`id`, `firstname`, `surname`, `address`, `postcode`, `city`, `phone`, `created_at`) 
              VALUES (:id, :firstname, :surname, :address, :postcode, :city, :phone, NOW())";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":id" => $id,
        ":firstname" => $firstname,
        ":surname" => $surname,
        ":address" => $address,
        ":postcode" => $postcode,
        ":city" => $city,
        ":phone" => $phoneNumber
      ]);

      // Render error page
    } catch (\PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }

  public function updateCustomer($id, $firstname, $surname, $address, $postcode, $city, $phoneNumber)
  {
    $result = [];
    $query = "UPDATE customers 
              SET 
                firstname = :firstname, 
                surname = :surname, 
                address = :address, 
                postcode = :postcode, 
                city = :city, 
                phone = :phone, 
                edited_at = NOW() 
              WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":id" => $id,
        ":firstname" => $firstname,
        ":surname" => $surname,
        ":address" => $address,
        ":postcode" => $postcode,
        ":city" => $city,
        ":phone" => $phoneNumber
      ]);

      // Render error page
    } catch (\PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }

  public function removeCustomer($id)
  {
    $result = [];
    $query = "DELETE FROM customers WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);
      $statement->execute([":id" => strtoupper($id)]);

      $result = $statement->rowCount();

      // Render error page
    } catch (\PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }
}
