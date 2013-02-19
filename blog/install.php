<?php

require_once("controller.php");
require_once("db.php");
        
function validate_on_submit(){
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
       
       if(empty($_POST['username'])){
           echo("Brugernavn skal udfyldes");
           die();
       }

       if(empty($_POST['password'])){
           echo("Kodeord skal udfyldes");
           die();
       }
       
       if(empty($_POST['password2'])){
           echo("Du skal gentage kodeordet");
           die();
       }
       
       if($_POST["password"] != $_POST["password2"]){
           echo("Kodeord er ikke ens");
           die();
       }
       
       if($_POST["username"] != $_POST["username2"]){
           echo("Brugernavne er ikke ens");
           die();
       }
       
       return true;
    }
    return false;
}

$user = db_query_get("SELECT * FROM users");

if($user == null){

    if(validate_on_submit()){
        
        
    	$sql = "INSERT INTO users (email, password, created_at) VALUES (?,?,NOW())";
    	db_query($sql, array($_POST['username'], $_POST['password']));
        
        flash("Oprettede bruger " . $_POST['username']);
        
        $_SESSION['user_id'] = db_last_insert_id();
        $_SESSION['username'] = $_POST['username'];
        
        header('Location: posts.php');
    }
    else{
        // Hvis der ikke er nogen bruger endnu gå til signup_form.html
        echo(render("signup_form.html"));    
    }
}
else{
    flash_error("Konto allerede oprettet!");
    echo(render("base.html"));
}
