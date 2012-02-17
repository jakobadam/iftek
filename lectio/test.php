<?php

require_once('facebook.php');

function test_save_user(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $obj = json_decode(file_get_contents('tokens/1'));   
    assert('$token == $obj->access_token');
}


test_save_user();

?>