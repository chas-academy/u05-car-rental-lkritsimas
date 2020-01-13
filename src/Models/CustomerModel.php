<?php

namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class CustomerModel extends AbstractModel
{
  public function getCustomers()
  {
    $result = [];
    // $query = "SELECT 
    //             vehicles.*,
    //             booking.vehicle_id,
    //             booking.customer_id,
    //             booking.created_at,
    //             makes.make,
    //             colors.color
    //           FROM vehicles
    //             LEFT JOIN makes ON makes.id = vehicles.make 
    //             LEFT JOIN colors ON colors.id = vehicles.color
    //             LEFT JOIN booking ON booking.vehicle_id = vehicles.id 
    //             LEFT JOIN customers ON customers.id = booking.customer_id 
    //           ORDER BY vehicles.created_at";    
    // $query = "SELECT 
    //             customers.*,
    //             booking.id AS booking_id
    //           FROM customers 
    //           LEFT JOIN booking ON booking.customer_id = customers.id
    //           ORDER BY customers.created_at";

    $query = "SELECT 
                customers.*
              FROM customers
              ORDER BY customers.created_at";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute();
      $result = $statement->fetchAll();

      // Throw exception if query fails
      if (!$result) throw new DatabaseException($this->db->errorInfo());
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
    $query = "SELECT 
                *
              FROM customers
              WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute([":id" => $id]);
      $result = $statement->fetch();

      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
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

      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
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

      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
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

      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch (\PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }
}
