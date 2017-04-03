<?php

$name = $_POST['username'];
$pass = $_POST['password'];
$time = time();


if(isset($name) && isset($pass)) {
	$hashpass = hash('sha512',$pass);
	try {
		// Create database connection using PHP Data Object (PDO)
		$db = new PDO("mysql:host=localhost;dbname=SecuriShop", "root", "root");
	

		// SQL injections possible
		if (!isset($_POST['sqler'])) {
			$sql = "SELECT * FROM user WHERE username='$name' AND password='$pass'";
		}

		
		if (isset($_POST['sqler'])) {
			$temp1 = htmlspecialchars($name);
			$temp2 = htmlspecialchars($pass);
			$sql = "SELECT * FROM user WHERE username='$temp1' AND password='$temp2'";
		}
		foreach ($db->query($sql) as $rows) {
			if ( $rows['username'] == $name ) {
				if ( isset($_POST['hasher']) && $rows['hashpass'] == $hashpass ) {
					if (!isset($_COOKIE['username'])) {
						// Set a cookie that expires after 5 days.
						setcookie('username', $name, $time+3600*24*5);
					}
				}
				if ( !isset($_POST['hasher']) && $rows['password'] == $pass ) {
					$_SESSION['username'] = $name;
					//$_SESSION['password'] = $pass;
					if (!isset($_COOKIE['username'])) {
						// Cookie expires after 5 days
						setcookie('username', $name, $time+3600*24*5);
					}
				}
				header('Location: welcome.php');
				
			}
		};

		// Close connection to database
		$db = NULL;
	}
	catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
}


?>