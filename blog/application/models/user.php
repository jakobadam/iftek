<?php

include("model.php");

class User extends Model{
    
    var $email;
    var $password_hash;
    var $password_salt;
    var $name;
}
?>
