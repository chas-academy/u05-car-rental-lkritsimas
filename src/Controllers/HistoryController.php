<?php

namespace CarRental\Controllers;

use CarRental\Models\BookingModel;

class HistoryController extends AbstractController
{
  public function get()
  {
    $bookingModel = new BookingModel($this->db);
    $bookings = $bookingModel->getBookings();
    $sum = 0;

    foreach ($bookings as $key => $booking) {
      if (!empty($booking["returned_at"])) {
        $rentDate = new \DateTime($booking["rented_at"]);
        $returnDate = new \DateTime($booking["returned_at"]);
        // Get difference between dates in days
        $diffDays = $returnDate->diff($rentDate)->format("%a");
        if ($diffDays == 0) $diffDays = 1;

        // Add amount of days customer has rented vehicle to bookings array
        $bookings[$key]['days'] = $diffDays;

        // Multiply price with amount of days
        $cost = (int) $booking["price"] * (int) $diffDays;
        $bookings[$key]['total_cost'] = $cost;

        // Add cost to sum
        $sum += $cost;
      }
    }

    return $this->render("History.html.twig", [
      "route" => "history",
      "bookings" => $bookings,
      "sum" => $sum
    ]);
  }
}
