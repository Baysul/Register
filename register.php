<?php

require_once "autoload.php";

final class Database extends PDO {

	private $config = [
		"Host" => "127.0.0.1",
		"User" => "root",
		"Pass" => "",
		"Name" => "kitsune"
	];

	private $connection = null;

	public function __construct() {
		$connectionString = sprintf("mysql:dbname=%s;host=%s", $this->config["Name"], $this->config["Host"]);

		parent::__construct($connectionString, $this->config["User"], $this->config["Pass"]);
	}

	public function addUser($username, $password, $color, $email = "none@kodomo.love") {
		$swid = $this->generateUniqueId();

		$hashedPassword = strtoupper(md5($password));

		$insertPenguin = "INSERT INTO `penguins` (`ID`, `Username`, `Nickname`, `Password`, `SWID`, `Email`, `RegistrationDate`, `Inventory`, `Color`, `Igloos`, `Floors`, `Locations`) VALUES ";
		$insertPenguin .= "(NULL, :Username, :Username, :Password, :Swid, :Email, :Date, :Color, :Color, :Igloos, :Floors, :Locations);";
		
		$insertStatement = $this->prepare($insertPenguin);
		$insertStatement->bindValue(":Username", $username);
		$insertStatement->bindValue(":Password", $hashedPassword);
		$insertStatement->bindValue(":Swid", $swid);
		$insertStatement->bindValue(":Email", $email);
		$insertStatement->bindValue(":Date", time());
		$insertStatement->bindValue(":Color", $color);
		$insertStatement->bindValue(":Igloos", "1|0");
		$insertStatement->bindValue(":Floors", "0|0");
		$insertStatement->bindValue(":Locations", "1|0");
		
		$insertStatement->execute();
		$insertStatement->closeCursor();
		
		$penguinId = $this->lastInsertId();

		$this->addActiveIgloo($penguinId);
		
		return $penguinId;
	}
	
	private function addActiveIgloo($penguinId) {
		$insertStatement = $this->prepare("INSERT INTO `igloos` (`ID`, `Owner`, `Likes`) VALUES (NULL, :Owner, '[]');");
		$insertStatement->bindValue(":Owner", $penguinId);
		$insertStatement->execute();
		$insertStatement->closeCursor();
		
		$iglooId = $this->lastInsertId();
		
		$setActiveIgloo = $this->prepare("UPDATE `penguins` SET `Igloo` = :Igloo WHERE ID = :Penguin;");
		$setActiveIgloo->bindValue(":Igloo", $iglooId);
		$setActiveIgloo->bindValue(":Penguin", $penguinId);
		$setActiveIgloo->execute();
		$setActiveIgloo->closeCursor();
	}
	
	public function usernameTaken($username) {
		$usernameTaken = "SELECT Username FROM `penguins` WHERE Username = :Username";
		
		$takenQuery = $this->prepare($usernameTaken);
		$takenQuery->bindValue(":Username", $username);
		$takenQuery->execute();
		
		$rowCount = $takenQuery->rowCount();
		$takenQuery->closeCursor();
		
		return $rowCount > 0;
	}

	private function generateUniqueId() {
		mt_srand((double)microtime() * 10000);
		
		$charid = md5(uniqid(rand(), true));
		$hyphen = chr(45);
		$uuid = chr(123)
			. substr($charid, 0, 8) . $hyphen
			. substr($charid, 8, 4) . $hyphen
			. substr($charid, 12, 4) . $hyphen
			. substr($charid, 16, 4) . $hyphen
			. substr($charid, 20, 12)
			. chr(125);
		
		return $uuid;
	}

}

function response($data) {
	die(json_encode($data));
}

function attemptDataRetrieval($key) {
	if(array_key_exists($key, $_POST)) {
		return $_POST[$key];
	}

	response([
		"success" => false,
		"message" => "<strong>Uh oh!</strong> Please fill out the form completely."
	]);
}

$recaptcha = new \ReCaptcha\ReCaptcha("secret");
$resp = $recaptcha->verify(attemptDataRetrieval("captcha"), $_SERVER["REMOTE_ADDR"]);
if(!$resp->isSuccess()) response(["success" => false, "message" => "<strong>Uh oh!</strong> Invalid captcha."]);

$username = attemptDataRetrieval("username");
$password = attemptDataRetrieval("password");
$color = attemptDataRetrieval("color");
$colors = range(1, 17);

if(strlen($username) < 4 || strlen($username) > 12) {
	$lengthWord = strlen($username) < 3 ? "short" : "long";
	response([
		"success" => false,
		"message" => "<strong>Uh oh!</strong> Username is too $lengthWord."
	]);
} elseif(strlen($password) < 4) {
	response([
		"success" => false,
		"message" => "<strong>Uh oh!</strong> Password is too short."
	]);
} elseif(!is_numeric($color) || !in_array($color, $colors)) {
	response([
		"success" => false,
		"message" => "<strong>Uh oh!</strong> Invalid color specified."
	]);
}

$db = new Database();

if($db->usernameTaken($username)) {
	response([
		"success" => false,
		"message" => "<strong>Uh oh!</strong> The username you've specified is already in use."
	]);
}

$playerId = $db->addUser($username, $password, $color);

response([
	"success" => true,
	"message" => "<strong>Hooray!</strong> You have successfully registered your account. Your player id is <strong>$playerId</strong>."
]);

?>