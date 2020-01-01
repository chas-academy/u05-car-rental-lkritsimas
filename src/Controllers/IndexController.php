<?php
namespace CarRental\Controllers;

class IndexController extends AbstractController {
  public function test(): string {
    $properties = ["test" => "Hello"];
    return $this->render("Index.html.twig", $properties);
  }
}
