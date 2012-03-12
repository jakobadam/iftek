<?php

require_once('models/users.php');

function testSaveUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    saveUser($user);
    
    $obj = json_decode(file_get_contents('db/1'));   
    if($token != $obj->access_token){
        echo('FEJL testSaveUser<br>'); 
    }
    else{
        echo('OK testSaveUser<br>');
    }
}

function testGetUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    saveUser($user);
    
    $local_user = getUser($user->id);
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
testGetUser();

?>