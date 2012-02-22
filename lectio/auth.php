<?php

require("base_controller.php");
require("facebook.php");

$code = $_GET['code'];
if(empty($code)){
    echo('code query param not supplied!');
    die();    
}

$access_token = fbGetAccessToken($code);
$user = fbGetOrCreateLocalUser($access_token);

$_SESSION['fbUser']  = $user;  
header('Location: index.php') ;

?>
