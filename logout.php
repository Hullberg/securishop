<?php

if (isset($_COOKIE['username'])) {
	setcookie('username', "", time()-1);
	unset($_SESSION['username']);
}
header('Location: index.php');
exit;

?>