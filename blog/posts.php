<?php

require_once("base_controller.php");
require_once("models/post.php");
require_once("dao/post_dao.php");


if(is_logged_in()){
    $posts = Post_DAO::all();
}
else{
    $posts = Post_DAO::all("WHERE is_published = 1");
}


echo(render("posts.html", array('posts'=>$posts)));

?>
