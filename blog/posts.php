<?php

include_once("base_controller.php");
include_once("models/post.php");

$posts = array(
    new Post(array(
    	id=>1,
        title=>"title 1",
        body=>"the body",
        user_id=>1,
        created_at=>"0000-00-00"
        )),
    new Post(array(
        id=>2,
        title=>"title 2",
        body=>"the body 2",
        user_id=>1,
        created_at=>"0000-00-00"
    )),
      new Post(array(
      id=>3,
        title=>"title 1",
        body=>"the body",
        user_id=>1,
        created_at=>"0000-00-00"
        )),
          new Post(array(
      id=>4,
        title=>"title 1",
        body=>"the body",
        user_id=>1,
        created_at=>"0000-00-00"
        )));

echo(render("posts.html", array(posts=>$posts)));

?>
