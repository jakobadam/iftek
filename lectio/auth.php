<?php

require("base_controller.php");
require("facebook.php");

$code = $_GET['code'];
if(empty($code)){
    echo('code query param not supplied!');
    die();    
}

$access_token = fbGetAccessToken($code);
$user = fbGetUser($access_token);

// Gem brugeren lokalt
fbSaveUser($user);

$_SESSION['fbUser']  = $user;  
header('Location: index.php') ;

?>
