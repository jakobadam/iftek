<?php

include_once("base_controller.php");
include_once("models/post.php");

$id = $_GET['id'];
//$post = get_object_or_404();

$post = new Post(array(
    	'id'=>1,
        'title'=>'title 1',
        'body'=>'the body',
        'user_id'=>1,
        'created_at'=>'0000-00-00'
        ));

echo(render("post.html", array('post'=>$post)));

?>
