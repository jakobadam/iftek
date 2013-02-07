<?php

require_once "controller.php";
require_once "db.php";

login_required();

$sql = "SELECT * FROM posts WHERE id = ?";
$post = db_query($sql, array(intval($_GET['id'])));

if(!$post){
    flash_error('Det post findes ikke!');
    die();    
} 

$sql = "DELETE FROM posts WHERE id = ?";
$post = db_query($sql, array(intval($_GET['id'])));

flash('Blog indlæget er blevet slettet!');
header('Location: posts.php');

?>
