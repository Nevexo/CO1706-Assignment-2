<?php
require_once 'vars.php';
require_once 'database.php';

class User {
  public int $Id = 0;
  public string $Username = '';
  public int $PricingPlanId = 0;

  function __construct(int $Id, string $Username, int $PricingPlanId) {
    $this->Id = $Id;
    $this->Username = $Username;
    $this->PricingPlanId = $PricingPlanId;
  }

  public function delete($Password) {
    // Delete this user from the database, requires the password
    // TODO
  }
}

class Users {
  static function generateId(): int
  {
    // Generate a new random ID
    global $pdo;

    while (true) {
      // Loop until a unique ID is created.
      $randid = rand(1000, getrandmax()); // Create new ID

      if ($pdo->query("SELECT * FROM users WHERE id = " . $randid)->rowCount() == 0) {
        // This ID doesn't exist, break the loop.
        break;
      }
    }

    return $randid;
  }

  public static function create($Username, $PlainTextPassword, $PricingPlanId): User
  {
    // Create a new user in the database
    global $pdo;

    // First, check the user doesn't already exist
    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
    $query->execute([$Username]);
    if ($query->rowCount() != 0) throw new Exception("User already exists.");

    // Now create the new user - first, create the SQL statement.
    $query = $pdo->prepare("INSERT INTO users (id, username, password, offer_id) VALUES (?, ?, ?, ?)");

    // Create the user ID
    $randid = Users::generateId();

    // Create the password hash
    $PasswordHash = password_hash($PlainTextPassword, PASSWORD_DEFAULT);

    // Execute SQL query
    $result = $query->execute([$randid, $Username, $PasswordHash, $PricingPlanId]);
    if (!$result) {
      throw new Error("Failed to create the user.");
    }

    // Return a user object
    return new User($randid, $Username, $PricingPlanId);
  }
}

try {
  $user = Users::create("Cameron", "yeet", 1);
  echo $user->Username;
} catch (Exception $e) {
  echo $e;
}
