<?php
// EcksMusic Offers System
// Cameron Paul Fleming - 2021

require_once "database.php";

class PricingPlan
{
  public $Id = 0;
  public $Name = "";
  public $Description = "";
  public $Price = 0;
  public $ImagePath = "";

  public function __construct($Id, $Title, $Description, $Price, $ImagePath)
  {
    $this->Id = $Id;
    $this->Name = $Title;
    $this->Description = $Description;
    $this->Price = $Price;
    $this->ImagePath = $ImagePath;
  }
}

class Offers
{
  static function dataToPricingPlan($offer): PricingPlan
  {
    // Convert object from SQL into PricingPlan object.
    return new PricingPlan(
      $offer['offer_id'],
      $offer['title'],
      $offer['description'],
      $offer['price'],
      $offer['image']
    );
  }

  public static function getAllOffers(): array
  {
    // Get all current offers
    global $pdo;

    $result = $pdo->query("SELECT * FROM offers;")->fetchAll(PDO::FETCH_ASSOC);
    if ($result == false) return [];

    // Convert database objects into PricingPlan objects.
    $PricingPlans = [];
    foreach ($result as $offer) {
      array_push($PricingPlans, Offers::dataToPricingPlan($offer));
    }

    return $PricingPlans;
  }

  public static function getOffer($offerId)
  {
    // Get a specific pricing plan from the database
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM offers WHERE offer_id = ?");
    $result = $query->execute([$offerId]);
    if ($result == false) return null;

    return self::dataToPricingPlan($query->fetch(PDO::FETCH_ASSOC));
  }
}