<?php

namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class BookingModel extends AbstractModel
{
  public function isBookingActive($id)
  {
    $result = [];
    $query = "SELECT COALESCE(returned_at, TRUE) AS active 
              FROM booking 
              WHERE customer_id = :id 
                AND returned_at IS NULL";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute([':id' => $id]);
      $result = $statement->fetch();

      if (!empty($result))
        $result = true;
      else
        $result = false;

      // Render error page
    } catch (DatabaseException $e) {
      $this->di->get("Twig_Environment")->render("Error.html.twig", [
        "code" => $e->getCode(),
        "message" => $e->getMessage()
      ]);
    }

    return $result;
  }

  public function getBookings()
  {
    $result = [];
    $query = "SELECT * FROM booking 
                JOIN customers ON customers.id = customer_id 
                JOIN vehicles ON vehicles.id = vehicle_id 
                JOIN makes ON makes.id = vehicles.make 
                JOIN colors ON colors.id = vehicles.color
              ORDER BY booking.rented_at";

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

  public function addBooking($customerId, $vehicleId)
  {
    $result = [];
    $query = "INSERT INTO booking (`customer_id`, `vehicle_id`, `rented_at`) 
              VALUES (:customerId, :vehicleId, NOW())";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":customerId" => $customerId,
        ":vehicleId" => $vehicleId
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

  public function addReturn($vehicleId)
  {
    $result = [];
    $query = "UPDATE booking SET `returned_at` = NOW() WHERE vehicle_id = :vehicleId";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $result = $statement->execute([
        ":vehicleId" => $vehicleId
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
}
