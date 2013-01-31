<?php

require_once("base_controller.php");
require_once("models/db.php");

$id = intval($_GET['id']);
$sql = "SELECT * FROM posts WHERE id = ?";
$post = db_query($sql, array($id));

if(!$post){
    flash_error('Det post findes ikke!');
    header('Location: posts.php');
    die();
} 

echo(render("post.html", array('post'=>$post)));

?>
