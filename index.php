<?php

if (isset($_COOKIE['username'])) {
		header('Location: welcome.php');
		exit;
}


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
					setcookie('username', $name, $time + 3600*24*5);
					session_start();
					$_SESSION['username'] = $name;
					header('Refresh:0');
				}
				if ( !isset($_POST['hasher']) && $rows['password'] == $pass ) {
					setcookie('username', $name, $time + 3600*24*5);
					session_start();
					$_SESSION['username'] = $name;
					header('Refresh:0');
				}
				
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


<html>
<head>
<title>SecuriShop</title>
<link rel="stylesheet" href="style.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="./js/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="./js/bootstrap/css/bootstrap-responsive.min.css" />


</head>

<body>

<form action = "index.php" method = "post">
	<input type="text" name="searchphrase">
	<input type="submit" value="Search">
</form>

<!--
<p>eae889ceda1452b34555b2b52b9f05d28a1e8ed8d5dc8c62362b90ee49746af1b99bf53cb3e58323d29c1dcc5b1203e45f824d10d87b1a63b9d6eec59a2f6740</p>
<p>adminpassword</p>
-->

<?php
	if (!isset($_COOKIE['username'])) {
	// Show form if not logged in
?>
	<form action = "index.php" method="POST">
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="submit" value="Submit">
	<label>Hash: </label>
	<input type="checkbox" name="hasher" value="OFF">
	<label>SQL: </label>
	<input type="checkbox" name="sqler" value="OFF">
	</form>
<?php 

}
?>

<!-- dropdown thingy for info -->
Hashing! &emsp;
<div class="dropdown">
  <img src="http://pccweb.ca/standrewsdresden/files/2016/03/Question-mark.png" style="width:10px;height:10px">
  <div class="dropdown-content">
    <p>Hashing is when you apply an algorithm to a string a certain amount of times, and cannot be undone.</p>
  </div>
</div>

<br><br>
<div style="height: 70%; overflow-y: scroll">
<ul class="thumbnails" id="items">
	
<?php // Will retrieve all items and put display them. pretty!
 	$phrase = $_POST["searchphrase"];
	try {
		$db = new PDO("mysql:host=localhost;dbname=SecuriShop", "root", "root");
		
		if (!isset($phrase)) {
			$sql = "SELECT * FROM items";
		}
		// If user has searched for something, retrieve similar name-items.
		if (isset($phrase)) {
			$sphrase = (string)htmlspecialchars($phrase);
			// If searchfield is empty, show all.
			if ($sphrase == "") {
				$sql = "SELECT * FROM items";
			}
			if ($sphrase != "") {
				// Any part of the searchphrase is shown.
				$sql = "SELECT * FROM items WHERE name LIKE '%$sphrase%'";
			}
		}
		foreach ($db->query($sql) as $row) {
			print "<li class='span3'>";
			print "<div class='thumbnail'>";
			print "<img src='" . $row['imgurl'] . "' alt='' style='height:200px;width:100px'>";
			print "<div class='caption'>";
			print "<h4>" . $row['name'] . "</h4>";
			print "<p>" . $row['price'] . "</p>";
			print "<a class='btn btn-success' href='#'>Add to Cart</a>";
			print "</div>";
			print "</div>";
			print "</li>";
		}
	}
	catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
?>
				
</ul>
</div>


</body>
</html>