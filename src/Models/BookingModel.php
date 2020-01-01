<?php
namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class BookingModel extends AbstractModel {
  public function getBookings() {
    $result = [];
    $query = "SELECT * FROM booking 
                JOIN customers ON customers.id = customer_id 
                JOIN vehicles ON vehicles.id = vehicle_id 
                JOIN makes ON makes.id = vehicles.make 
                JOIN colors ON colors.id = vehicles.color
              ORDER BY booking.created_at";

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
}
