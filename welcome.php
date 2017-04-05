<?php 

if (isset($_COOKIE['username'])) {
	$_SESSION['username'] = $_COOKIE['username'];
}
if (isset($_SESSION['username'])) {
	$_COOKIE['username'] = $_SESSION['username'];
}
echo $_COOKIE['username'] . " cookie!";

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
				<a class="brand" href="/">SecuriShop</a>

				<div class="nav-collapse collapse">

					<!--<form action = "welcome.php" method = "post">
						<input type="text" name="searchphrase">
						<input type="submit" value="Search">
					</form>-->
					<form class="navbar-form form-search pull-right" method="post" action="welcome.php">
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
						  <img src="http://pccweb.ca/standrewsdresden/files/2016/03/Question-mark.png" style="width:10px;height:10px">
						  <div class="dropdown-content">
						    <p>Hashing is when you apply an algorithm to a string a certain amount of times, and cannot be undone.</p>
						  </div>
						</div>

						<br><br>
						SQL Injection
						<input type="button" id="m1" value="OFF" style="color:blue; float:right" onclick="toggle(this, 'm1');"> <br><br>
						XSS
						<input type="button" id="m2" value="OFF" style="color:blue; float:right" onclick="toggle(this, 'm2');"> <br><br>
						Encryption
						<input type="button" id="m3" value="OFF" style="color:blue; float:right" onclick="toggle(this, 'm3');">
					</form>
				</div>

				<div class="well">

					<div class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-shopping-cart"></i>
							4 item - $999.99
							<b class="caret"></b></a>
						</a>
						<div class="dropdown-menu well" role="menu" aria-labelledby="dLabel">
							<p>Item x 1 <span class="pull-right">$333.33</span></p>
							<p>Item x 1 <span class="pull-right">$333.33</span></p>
							<p>Item x 1 <span class="pull-right">$333.33</span></p>
							<a href="#" class="btn btn-primary">Checkout</a>
						</div>
					</div>

				</div>


				<div class="well">

					<!--<form action="logout.php" method="post">
						<input type="submit" value="Logout">
					</form>-->
					<form class="form login-form" id="form_id" method="post" name="loginform" action="logout.php">
						<h2>Sign out</h2>
						<div>
							<button type="submit" class="btn btn-success" value="Logout" id="submit">Logout</button>
						</div>
					</form>
				</div>
			</div>

			<div class="span9">
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

							$db = NULL;
						}
						catch (PDOException $e) {
						    print "Error!: " . $e->getMessage() . "<br/>";
							die();
						}
					?>
									
				</ul>
			</div>
		</div>
	</div>
	
	<hr />
	<!--
	<footer id="footer" class="vspace20">
		<div class="container">
			<div class="row">
				<div class="span4">
					<h4>Info</h4>
					<ul class="nav nav-stacked">
						<li><a href="#">Sell Conditions</a>
						<li><a href="#">Shipping Costs</a>
						<li><a href="#">Shipping Conditions</a>
						<li><a href="#">Returns</a>
						<li><a href="#">About Us</a>
					</ul>
				</div> 

				<div class="span4">
					<h4>Location and Contacts</h4>
					<p><i class="icon-map-marker"></i>Uppsala</p>
					<p><i class="icon-envelope"></i>&nbsp;Email: info@mydomain.com</p>
					<p><i class="icon-globe"></i>&nbsp;Web: http://www.mydomain.com</p>
				</div>

				<div class="span4">
					<h4>Newsletter</h4>
					<p>Write you email to subscribe to our Newsletter service. Thanks!</p>
					<form class="form-newsletter">
						<div class="input-append">
							<input type="email" class="span2" placeholder="your email">
							<button type="submit" class="btn">Subscribe</button>
						</div>
					</form>
				</div>

			</div>

			<div class="row">
				<div class="span6">
					<p>&copy; Copyright 2012.&nbsp;<a href="#">Privacy</a>&nbsp;&amp;&nbsp;<a href="#">Terms and Conditions</a></p>
				</div>
				<div class="span6">
					<a class="pull-right" href="http://www.responsivewebmobile.com" target="_blank">credits by Responsive Web Mobile</a>
				</div>
			</div>
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