<?php
namespace CarRental\Models;

use \PDO;
use CarRental\Exceptions\DatabaseException;

class CustomerModel extends AbstractModel {
  public function getCustomers() {
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
    $query = "SELECT 
                customers.*,
                booking.id AS booking_id
              FROM customers 
              LEFT JOIN booking ON booking.customer_id = customers.id
              ORDER BY customers.created_at";

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
