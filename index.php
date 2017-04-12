<?php
if (!isset($_SESSION)) {
	session_start();
}

// Security measures
/*if (!isset($_SESSION['sql'])) {
	$_SESSION['sql'] = 'OFF';
	header('Refresh:0');
}

$sqll = $_POST['sqlbutton'];
if ($sqll == "ON") {
	echo "test";
}
if ($sqll == "OFF") {
	echo "tost";
}
if (!isset($_POST['sqlbutton'])) {
	echo "button not pushed";
}
if (isset($_POST['sqlbutton'])) {
	echo "button pushed";
}*/

$name = $_POST['username'];
$pass = $_POST['password'];
$time = time();

// Login
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
					if (isset($_POST['rememberme'])) {setcookie('username', $name, $time + 3600*24*5);}
					session_start();
					$_SESSION['username'] = $name;
					header('Refresh:0');
				}
				if ( !isset($_POST['hasher']) && $rows['password'] == $pass ) {
					if (isset($_POST['rememberme'])) {setcookie('username', $name, $time + 3600*24*5);}
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

// Add to cart
$prod_id = $_POST['product_id'];
$prod_name = $_POST['product_name'];
$prod_price = $_POST['product_price'];
if (isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['product_price'])) {
	
	$new_product['prod_id'] = $prod_id;
	$new_product['prod_name'] = $prod_name;
	$new_product['price'] = $prod_price;

	// If already in cart, add one more
	if (isset($_SESSION['cart_products'][$new_product['prod_id']])) {
		$new_product['amount'] = $_SESSION['cart_products'][$new_product['prod_id']][amount] + 1;
	}

	if (!isset($_SESSION['cart_products'][$new_product['prod_id']])) {
		$new_product['amount'] = 1;
	}
	$_SESSION['cart_products'][$new_product['prod_id']] = $new_product;
}

// Clear cart
if (isset($_POST['clearcart'])) {
	unset($_SESSION['cart_products']);
}
?>

<html>
<head>
<title>SecuriShop</title>
<link rel="stylesheet" href="style.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="./js/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="./js/bootstrap/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css" />

</head>

<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse" type="button">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="/SecuriShop/index.php">SecuriShop</a>

				<div class="nav-collapse collapse">

					<form class="navbar-form form-search pull-right" method="post" action="index.php">
						<input id="Search" name="searchphrase" type="text" class="input-medium search-query">
						<button type="submit" class="btn">Search</button>
					</form>
				</div>

			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="span3">


				<div class="well">
					<h4>Security Measures</h4>
					<form>

						<!-- dropdown thingy for info -->
						Hashing! &emsp;
						<div class="dropdown">
						  <img src="https://image.flaticon.com/icons/png/512/8/8235.png" style="width:15px;height:15px">
						  <div class="dropdown-content">
						    <p>Hashing is when you apply an algorithm to a string a certain amount of times, and cannot be undone.</p>
						  </div>
						</div>

						<?php
						echo "<br><br>";
						echo "SQL Injection";
						echo "<form method='post' action='index.php'>";
						echo "<input type='hidden' name='sqlbutton'></input>";
						if ($_SESSION['sql'] == 'ON') {
							echo "<button style='color:blue; float:right'>ON</button>";
						}
						if ($_SESSION['sql'] == 'OFF') {
							echo "<button style='color:blue;float:right;'>OFF</button>";
						}
						//echo "<input type='submit' value='".$_SESSION['sql']."' style='color:blue; float:right'>";
						echo "</form>";
						echo "XSS";
						echo "<input type='button' id='m2' value='OFF' style='color:blue; float:right'> <br><br>";
						echo "Encryption";
						echo "<input type='button' id='m3' value='OFF' style='color:blue; float:right'>";
						?>
					</form>
				</div>

				<div class="well">

					<div class="dropdown">
						<?php
							if (isset($_SESSION['cart_products']) && count($_SESSION['cart_products']) > 0) {
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-shopping-cart"></i>
							<?php // Count amount of items in cart, and the sum of all prices.
							$total_sum = 0;
							$tempcount = 0;
							foreach ($_SESSION['cart_products'] as $prod) {
								$total_sum = $total_sum + $prod[price]*$prod[amount];
								$tempcount = $tempcount + $prod[amount];
							}
							$_SESSION['total_sum'] = $total_sum;
							echo $tempcount.' items - '.$total_sum.' SEK';
							?>
							<b class="caret"></b>
						</a>
						<div class="dropdown-menu well" role="menu" aria-labelledby="dLabel">
							<?php // Populate cart with items previously added
					
							foreach($_SESSION['cart_products'] as $prod) {
								echo "<p>".$prod[prod_name]." x ".$prod[amount]." <span class='pull-right'>".$prod[price]."</span></p>";
							}
							echo "<form method='post' action='index.php' style='display:inline-block;'>";
							echo "<input type='hidden' name='clearcart'></input>";
							echo "<button class='btn btn-success'>Clear Cart</button>";
							echo "</form>";
							echo "<form method='post' action='checkout.php' style='display:inline-block;margin-left:3em'>";
							echo "<input type='hidden' name='checkoutpurchase' value='".$_SESSION['cart_products']."'></input>";
							echo "<button class='btn btn-success'>Checkout</button>";
							echo "</form>";
							

							?>
						</div>
						<?php // If the cart is empty
							}
							if (!isset($_SESSION['cart_products']) && count($_SESSION['cart_products']) < 1) {
						?>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-shopping-cart"></i>
								0 items
								<b class="caret"></b>
							</a>
							<div class="dropdown-menu well" role="menu" aria-labelledby="dLabel">
								<p>Cart is empty</p>
							</div>
						<?php
							}
						?>
					</div>

				</div>


				<div class="well">

					<?php
						if (!isset($_COOKIE['username'])) {
						// Show form if not logged in
					?>

					<form class="form login-form" id="form_id" method="post" name="loginform" action="index.php">
						<h2>Sign in</h2>
						<div>
							<label>Username</label>
							<input id="Username" name="username" type="text" />

							<label>Password</label>
							<input id="Password" name="password" type="password" />

							<label class="checkbox inline">
								<input type="checkbox" id="RememberMe" name="rememberme" value="option1"> Remember me
							</label>

							<br /><br />
							<button type="submit" class="btn btn-success" value="Login" id="submit">Login</button>
						</div>
						<br />
					</form>
					<?php 

						}
						if (isset($_COOKIE['username'])) {
					
						echo "<form class='form login-form' id='form_id' method='post' name='loginform' action='logout.php'>";
						echo "<h3>Logged in as: ".$_COOKIE['username']."</h3>";
						echo "<input type='hidden' name='logoutuglysolution' value='logout'></input>";
						echo "<input type='submit' class='btn btn-success' value='Log out' id='submit'></input>";
						echo "</form>";
					}
					?>
				</div>
			</div>


			<div class="span9" style="overflow-y: scroll">
				<ul class="thumbnails">
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
								echo "<li class='span3'>";
								echo "<div class='thumbnail'>";
								echo "<img src='" . $row['imgurl'] . "' alt='' style='height:200px;width:100px'>";
								echo "<div class='caption'>";
								echo "<h4>" . $row['name'] . "</h4>";
								echo "<p>" . $row['price'] . "</p>";
								echo "<form method='POST' action='index.php'>";
								echo "<input type='hidden' name='product_id' value='".$row['id']."' />";
								echo "<input type='hidden' name='product_name' value='".$row['name']."' />";
								echo "<input type='hidden' name='product_price' value='".$row['price']."' />";
								echo "<input type='hidden' name='type' value='add_product' />";
								echo "<button class='btn btn-success'>Add to Cart</button>";
								echo "</form>";
								echo "</div>";
								echo "</div>";
								echo "</li>";
							}
						}
						catch (PDOException $e) {
						    print "Error!: " . $e->getMessage() . "<br/>";
							die();
						}
					?>
			</div>
		</div>
	</div>
	
	
	<!--
	<hr />
	<footer id="footer" class="vspace20">
		<div class="container">
			Cred: rwm-ecommerce for bootstrap template 
		</div>
	</footer>
	-->

	<script src="./js/jquery-1.10.0.min.js"></script>
	<script src="./js/bootstrap/js/bootstrap.min.js"></script>
	<script src="./js/holder.js"></script>
	<script src="./js/script.js"></script>
</body>

</body>
</html>