<?php

require_once("controller.php");
require_once("db.php");

function validate_on_submit(){
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
       if(empty($_POST['email'])){
           echo "Email skal udfyldes";
           die();
       }

       if(empty($_POST['password'])){
           echo "Password skal udfyldes";
           die();
       }
       
       return true;
    }
    return false;
}

if(validate_on_submit()){

    $email = $_POST['email'];
    $password = $_POST['password'];
    
	$sql = "SELECT * FROM users WHERE email = ? and password = ? LIMIT 1";

    $user = db_query_get($sql, array($email, $password));
		
	if(!$user){
	    flash_error('Kunne ikke logge ind!');
	 	echo(render("login_form.html"));
		die();
	}

	// TODO: change email -> username
	$_SESSION['username'] = $user['email'];
	$_SESSION['user_id'] = $user['id'];

	flash('Velkommen tilbage!');
	header('Location: posts.php');
	die();
	
}

echo(render("login_form.html"));

?>
