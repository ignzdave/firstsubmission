<?PHP
include_once "connection.php";
class User {
	private $user_id, $name, $email, $gender;
	protected $connection;
	public function getUserId() {
		return ($this->user_id);
	}

	public function setUserId($userId) {
		$this->user_id = $userId;
	}

	public function getFullName() {
		return ($this->name);
	}

	public function setFullName($fullName) {
		$this->name = $fullName;
	}

	public function getEmail() {
		return ($this->email);
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getGender() {
		return ($this->gender);
	}
	public function setGender($gender) {
		$this->gender = $gender;
	}

	function read() {

		$this->connection = new Connection();
		$conn = $this->connection->openConnection();

		$read = $conn->prepare("SELECT FullName, Email, Gender, CONVERT(varchar,Created,105) as Created FROM [dbo].[User]");
		$read->execute();

		return $read->fetchAll(PDO::FETCH_ASSOC);

		$this->connection->closeConnection();

	}

	function create() {
		$this->connection = new Connection();
		$conn = $this->connection->openConnection();
		$insert = $conn->prepare("INSERT INTO [dbo].[User] (FullName, Email, Gender, Created) VALUES (:name, :email, :gender, :created)");

		try {
			$conn->beginTransaction();
			$result = $insert->execute(array(
				':name' => $this->getFullName(),
				':email' => $this->getEmail(),
				':gender' => $this->getGender(),
				':created' => date('Y-m-d H:i:s')
			));

			if ($result) {
				$conn->commit();
				echo "<i style='color:green'>User Saved Successfully..! <i>";
				header('location:index.php');
			} else {
				echo "<i style='color:red'>There are some problem while saving the Data...! <i>";
			}
		} catch (PDOExecption $e) {
			$conn->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}

		$this->connection->closeConnection();
	}

}
?>
