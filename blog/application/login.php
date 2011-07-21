<?php

include("base_controller.php");
include("models/user.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
	$email= $_POST['email'];
	$password = $_POST['password'];
	
	// FIXME: fetch from db
	$user = array(email=>'jakob', password =>'foobar');
	
	if(!$user){
	 	echo(render("login_form.html", array(errors=>array('Kunne ikke logge ind!'))));
		die();
	}
	$_SESSION['email'] = $user->email;
	flash('Velkommen tilbage!');
	header('Location: /');
	die();
}

echo(render("login_form.html"));

?>
