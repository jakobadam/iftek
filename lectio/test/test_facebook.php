<?php

require_once('facebook.php');

function testSaveUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $obj = json_decode(file_get_contents('db/1'));   
    if($token != $obj->access_token){
        echo('FEJL testSaveUser<br>'); 
    }
    else{
        echo('OK testSaveUser<br>');
    }
}

function testGetLocalUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $local_user = fbGetLocalUser($user->id);
    if(!property_exists($local_user, 'access_token')){
        echo('FEJL testGetLocalUser<br>');
    }
    else if($local_user->access_token != 'foobar'){
        echo('FEJL testGetLocalUser<br>');
    }
    else{
        echo('OK testGetLocalUser<br>');
    }
}

testSaveUser();
testGetLocalUser();


?>