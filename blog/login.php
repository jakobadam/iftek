<?php

include_once("base_controller.php");
include_once("forms/login_form.php");
include_once("models/user.php");

$form = new LoginForm($_POST);

if($form->validate_on_submit()){

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

echo(render("login_form.html", array(form=>$form)));

?>
