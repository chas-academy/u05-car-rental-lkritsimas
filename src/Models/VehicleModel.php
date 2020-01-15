<?php

namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class VehicleModel extends AbstractModel
{
  public function getVehicles()
  {
    $result = [];
    $query = "SELECT * FROM vehicles ORDER BY created_at";

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

  public function getVehicle($id)
  {
    $result = [];
    $query = "SELECT * FROM vehicles WHERE id = :id";

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

  public function getColors()
  {
    $result = [];
    $query = "SELECT * FROM colors";

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

  public function getMakes()
  {
    $result = [];
    $query = "SELECT * FROM makes";

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

  public function addVehicle($id, $make, $color, $year, $price)
  {
    $result = [];
    $query = "INSERT INTO vehicles (`id`, `make`, `color`, `year`, `price`, `created_at`) 
              VALUES (:id, :make, :color, :year, :price, NOW())";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":id" => $id,
        ":make" => $make,
        ":color" => $color,
        ":year" => $year,
        ":price" => $price
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

  public function updateVehicle($id, $make, $color, $year, $price)
  {
    $result = [];
    $query = "UPDATE vehicles 
              SET
                `make` = :make, 
                `color` = :color, 
                `year` = :year, 
                `price` = :price 
              WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":id" => $id,
        ":make" => $make,
        ":color" => $color,
        ":year" => $year,
        ":price" => $price
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

  public function removeVehicle($id)
  {
    $result = [];
    $query = "DELETE FROM vehicles WHERE id = :id";

    try {
      // Perform query
      $statement = $this->db->prepare($query);
      $statement->execute([":id" => $id]);

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
