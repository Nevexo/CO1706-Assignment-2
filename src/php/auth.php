<?php
// Authentication functions & classes
require_once 'vars.php';
require_once 'offers.php';
require_once 'database.php';

class User
{
  public $Id = 0;
  public $Username = '';
  public $PricingPlanId = 0;
  public $PricingPlan;

  function __construct(int $Id, string $Username, int $PricingPlanId)
  {
    $this->Id = $Id;
    $this->Username = $Username;
    $this->PricingPlanId = $PricingPlanId;
    $this->PricingPlan = Offers::getOffer($PricingPlanId);
  }

  public function changePricingPlan($NewPlanId): User
  {
    // Update the pricing plan for this user
    global $pdo;

    $newPlan = Offers::getOffer($NewPlanId);
    if ($newPlan == null) throw new Exception("InvalidPlan");

    // Update the database
    $query = $pdo->prepare("UPDATE users SET offer_id = ? WHERE id = ?");
    $result = $query->execute([$NewPlanId, $this->Id]);

    if (!$result) throw new Exception("AccountUpdateFailed");

    // Update this user object
    $this->PricingPlanId = $NewPlanId;
    $this->PricingPlan = $newPlan;

    return $this;
  }

  function confirmPassword($Password): bool
  {
    // Check a password is valid
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$this->Id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result == false) throw new Exception("QueryError");

    return password_verify($Password, $result['password']);
  }

  public function changePassword($Password, $NewPassword): bool
  {
    // Change the user's password
    global $pdo;
    global $PASSWORD_MIN_LENGTH;

    try {
      $PasswdCheck = $this->confirmPassword($Password);
    } catch (Exception $e) {
      throw new Exception("PasswordCheckFailed");
    }

    if (!$PasswdCheck) throw new Exception("InvalidPassword");
    if (strlen($NewPassword) < $PASSWORD_MIN_LENGTH) throw new Exception("InvalidNewPassword");

    $NewPasswordHash = password_hash($NewPassword, PASSWORD_DEFAULT);
    $query = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $result = $query->execute([$NewPasswordHash, $this->Id]);

    if (!$result) throw new Exception("PasswordUpdateFailed");
    return true;
  }

  public function delete($Password): bool
  {
    // Delete this user from the database, requires the password
    global $pdo;

    try {
      $PasswdCheck = $this->confirmPassword($Password);
    } catch (Exception $e) {
      throw new Exception("PasswordCheckFailed");
    }
    if (!$PasswdCheck) throw new Exception("InvalidPassword");

    // Delete reviews first, and then remove the user.
    // Reviews have the user_id as an FK so this would fail without deleting them first.
    $query = $pdo->prepare("DELETE FROM reviews WHERE author_id = :userid; DELETE FROM users WHERE id = :userid;");
    $query->bindValue("userid", $this->Id);
    $result = $query->execute();

    if (!$result) throw new Exception("AccountDeleteFailed");
    return true;
  }
}

class Users
{
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
    global $ENABLE_REGISTRATION;

    // Check for missing parameters
    if ($Username == "") throw new Exception("MissingUsername");
    if ($PlainTextPassword == "") throw new Exception("MissingPassword");
    if ($PricingPlanId == "") throw new Exception("MissingPricingPlan");
    if (!$ENABLE_REGISTRATION) throw new Exception("RegistrationDisabled");

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