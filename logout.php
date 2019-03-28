<?php include("funciones/init.php");



session_destroy();

if(isset($_COOKIE['email'])){
	unset($_COOKIE['email']);

	setcookie('email','',time()-86400);
}

direccionar("login.php");


?>