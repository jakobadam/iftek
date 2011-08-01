<?php

require_once("base_controller.php");
require_once("models/post.php");
require_once("dao/post_dao.php");


if(is_logged_in()){
    $posts = Post_DAO::get_all_posts();
}
else{
    $posts = Post_DAO::get_all_published_posts();
}


echo(render("posts.html", array('posts'=>$posts)));

?>
