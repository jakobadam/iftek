<?php

require_once("base_controller.php");
require_once("models/post.php");
require_once("dao/post_dao.php");

$id = $_GET['id'];
$post = Post_DAO::get_post_by_id($_GET['id']);
if(!$post){
    flash_error('Det post findes ikke!');
    die();    
} 

echo(render("post.html", array('post'=>$post)));

?>
