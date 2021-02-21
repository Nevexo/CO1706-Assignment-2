<?php
// Authentication functions & classes
require_once 'vars.php';
require_once 'offers.php';
require_once 'database.php';

class User {
  public $Id = 0;
  public $Username = '';
  public $PricingPlanId = 0;
  public $PricingPlan;

  function __construct(int $Id, string $Username, int $PricingPlanId) {
    $this->Id = $Id;
    $this->Username = $Username;
    $this->PricingPlanId = $PricingPlanId;
    $this->PricingPlan = Offers::getOffer($PricingPlanId);
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

  public static function login($Username, $Password): User
  {
    // Verify a user's password against the password stored in the database.
    // While this function knows which of the username/password is invalid, it always returns "InvalidUsernamePassword"
    // to protect the user's privacy.

    // Expect: User
    // Catch: InvalidUsernamePassword / QueryError
    global $pdo;

    // Safe the username
    $Username = htmlspecialchars($Username);

    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([
      $Username
    ]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    // Compare passwords
    if ($query == false) throw new Exception("QueryError");
    if ($query->rowCount() == 0) throw new Exception("InvalidUsernamePassword");
    if (!password_verify($Password, $data[0]['password'])) throw new Exception("InvalidUsernamePassword");

    // If everything passes, return a new user object
    return new User(
      $data[0]['id'],
      $data[0]['username'],
      $data[0]['offer_id']
    );
  }

  public static function create($Username, $PlainTextPassword, $PricingPlanId): User
  {
    // Create a new user in the database

    // Expect: User
    // Catch: UserAlreadyExists / QueryError
    global $pdo;
    global $PASSWORD_MIN_LENGTH;

    // Check for missing parameters
    if ($Username == "") throw new Exception("MissingUsername");
    if ($PlainTextPassword == "") throw new Exception("MissingPassword");
    if ($PricingPlanId == "") throw new Exception("MissingPricingPlan");

    // Check password complexity requirements
    if (strlen($PlainTextPassword) < $PASSWORD_MIN_LENGTH) throw new Exception("PasswordTooShort");

    // Safe the username from HTML injection
    $Username = htmlspecialchars($Username);

    // First, check the user doesn't already exist
    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
    $query->execute([
      $Username
    ]);
    if ($query->rowCount() != 0) throw new Exception("UserAlreadyExists");

    // Now create the new user - first, create the SQL statement.
    $query = $pdo->prepare("INSERT INTO users (id, username, password, offer_id) VALUES (?, ?, ?, ?)");

    // Create the user ID
    $randid = Users::generateId();

    // Create the password hash
    $PasswordHash = password_hash($PlainTextPassword, PASSWORD_DEFAULT);

    // Execute SQL query
    $result = $query->execute([$randid, $Username, $PasswordHash, $PricingPlanId]);
    if (!$result) {
      throw new Error("QueryError");
    }

    // Return a user object
    return new User($randid, $Username, $PricingPlanId);
  }
}