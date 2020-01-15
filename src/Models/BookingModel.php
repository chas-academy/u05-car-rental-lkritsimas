<?php

namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class BookingModel extends AbstractModel
{
  public function getBookingDetails($id, $type = 'customer')
  {
    $result = [];
    $query = "SELECT 
                customer_id,
                rented_at, 
                COALESCE(returned_at, TRUE) AS active 
              FROM booking";
    if ($type === 'customer')
      $query .= " WHERE customer_id = :id";
    else if ($type === 'vehicle')
      $query .= " WHERE vehicle_id = :id";
    $query .= " AND returned_at IS NULL";

    try {
      // Perform query
      $statement = $this->db->prepare($query);

      $statement->execute([':id' => $id]);
      $result = $statement->fetch();
      // $result = (!empty($result) ? true : false);
      $result = [
        "customer_id" => $result["customer_id"],
        "rented_at" => $result["rented_at"],
        "active" => (bool) $result["active"],
      ];

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
              ORDER BY booking.returned_at DESC";

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
    $query = "UPDATE booking SET returned_at = NOW() 
              WHERE vehicle_id = :vehicleId AND returned_at IS NULL";

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
