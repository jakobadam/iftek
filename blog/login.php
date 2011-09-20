<?php

require_once("base_controller.php");
require_once("forms/login_form.php");
require_once("models/user.php");

$form = new LoginForm($_POST);

if($form->validate_on_submit()){

    // FIXME: get_user(...)
    $user = User::get_by_email_and_password($form->email->value, $form->password->value);
	
	if(!$user){
	    flash_error('Kunne ikke logge ind!');
	 	echo(render("login_form.html"));
		die();
	}

	$_SESSION['user_email'] = $user->email;
	$_SESSION['user_id'] = $user->id;

	flash('Velkommen tilbage!');
	header('Location: posts.php');
	die();
	
}

echo(render("login_form.html", array(form=>$form)));

?>
