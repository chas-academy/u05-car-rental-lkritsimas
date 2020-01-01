<?php
namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class VehicleModel extends AbstractModel {
  public function getVehicles() {
    $result = [];
    $query = "SELECT 
                vehicles.*,
                booking.vehicle_id,
                booking.customer_id,
                booking.created_at,
                makes.make,
                colors.color
              FROM vehicles
                LEFT JOIN makes ON makes.id = vehicles.make 
                LEFT JOIN colors ON colors.id = vehicles.color
                LEFT JOIN booking ON booking.vehicle_id = vehicles.id 
                LEFT JOIN customers ON customers.id = booking.customer_id 
              ORDER BY vehicles.created_at";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute();
      $result = $statement->fetchAll();

      // Throw exception if query fails
      if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch(DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }

  public function getColors() {
    $result = [];
    $query = "SELECT * FROM colors";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute();
      $result = $statement->fetchAll();

      // Throw exception if query fails
      if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch(DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }  

  public function getMakes() {
    $result = [];
    $query = "SELECT * FROM makes";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute();
      $result = $statement->fetchAll();

      // Throw exception if query fails
      if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch(DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }  

  public function addVehicle($id, $make, $color, $year, $price) {
    $result = [];
    $query = "INSERT INTO vehicles (`id`, `make`, `color`, `year`, `price`, `created_at`) 
              VALUES (:id, :make, :color, :year, :price, NOW())";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute([
        ":id" => strtoupper($id), 
        ":make" => $make, 
        ":color" => $color, 
        ":year" => $year, 
        ":price" => $price 
      ]);

      $result = $this->db->lastInsertId('id');
      
      var_dump($this->db->lastInsertId('id'));
      var_dump($this->db->errorInfo());
      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch(PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }

  public function removeVehicle($id) {
    $result = [];
    $query = "DELETE FROM vehicles WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);
      $statement->execute([":id" => strtoupper($id)]);

      $result = $statement->rowCount();

      // Throw exception if query fails
      // if (!$result) throw new DatabaseException($this->db->errorInfo());
    } catch(PDOException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }
}
