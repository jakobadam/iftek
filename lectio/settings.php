<?php

require_once('base_controller.php');
require_once('facebook.php');
require_once('models/users.php');

$context = array();
$user = null;

if(array_key_exists('user', $_SESSION)){
    $user = $_SESSION['user'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($user)){
        echo 'Du skal være logget ind!';
        die();
    }
    $user->lectio_id = $_POST['lectio_id'];
    saveUser($user);

    flash('Indstillinger opdateret!');
    header('Location: index.php');
    die();
}       
else{
    if(isset($user)){
        $context['user'] = $user;
        $context['update_url'] = 'http://' 
        . $_SERVER['SERVER_NAME']
        . dirname($_SERVER['REQUEST_URI']) 
        . '/update.php?user_id=' . $user->id 
        . '&access_token=' . $user->access_token;    
    }
    else{
        $context['login_url'] = fbGetLoginURL();     
    }
    echo(render("settings.html", $context));
}
?>