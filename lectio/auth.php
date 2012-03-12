<?php

/**
 * Håndterer requests fra facebook efter login. 
 */

require("base_controller.php");
require("facebook.php");
require("models/users.php");

// code er en værdi som facebook giver os ved bruger login
$code = $_GET['code'];
if(empty($code)){
    echo('code query param not supplied!');
    die();    
}

// code bruges til at få fat i et token, som kan bruges til
// at poste på vegne af brugeren.
$access_token = fbGetAccessToken($code);
$fb_user = fbGetUser($access_token);

$user = getUser($fb_user->id);

// gem brugeren lokalt hvis hun ikke eksisterer.
if(empty($user)){
    $fb_user->access_token = $access_token;
    saveUser($fb_user);
}

$_SESSION['user']  = $user;  
header('Location: index.php') ;

?>
