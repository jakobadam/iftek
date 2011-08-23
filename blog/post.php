<?php

require_once("base_controller.php");
require_once("models/post.php");

$post = Post::get(intval($_GET['id']));
if(!$post){
    flash_error('Det post findes ikke!');
    header('Location: ' . url_root());
    die();
} 

echo(render("post.html", array('post'=>$post)));

?>
