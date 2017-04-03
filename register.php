<html>

<?php 

$name = $_POST['username'];
$pass = $_POST['password'];
$existBool = false;
$amtUsers = 0;

if(isset($name) && isset($pass)) {
	try {
		// Create database connection using PHP Data Object (PDO)
		$db = new PDO("mysql:host=localhost;dbname=SecuriShop", "root", "root");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Identify name of table within database
		$table = 'user';

		// Create the query - here we grab everything from the table
		$stmt = $db->query('SELECT * from '.$table);
		
		// See if name is taken
		while($rows = $stmt->fetch()) {
			if ($rows['username'] == $name) {
				$existBool = true;
				echo 'already exists <br />';
			}
			$amtUsers++;
		}

		if ($existBool == false) {
			$hashpass = hash('sha512',$pass);
			$sql = "INSERT INTO user (id,username,password,hashpass,cred) VALUES ('$amtUsers','$name','$pass','$hashpass',0)";
			$db->exec($sql);
			echo $amtUsers;
			echo $name;
			echo $pass;
			echo $hashpass;
			echo 'success! <br />';
		}
		// Close connection
		$db = NULL;
	}
	catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
}
?>

<form action = "register.php" method = "post">
<input type="text" name="username">
<input type="password" name="password">
<input type="submit" value="Submit">
</form>

</html>