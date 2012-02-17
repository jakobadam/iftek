<?php

require_once("base_controller.php");
require_once("facebook.php");

$context = array();
$user = null;

if(array_key_exists('fbUser', $_SESSION)){
    $user = $_SESSION['fbUser'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($user)){
        echo 'Du skal være logget ind!';
        die();
    }
    $user->lectio_id = $_POST['lectio_id'];
    fbSaveUser($user);

    flash('Indstillinger opdateret!');
    header('Location: index.php');
    die();
}       
else{
    $context['user'] = $user;
    $context['login_url'] = fbGetLoginURL(); 
    echo(render("settings.html", $context));
}
?>