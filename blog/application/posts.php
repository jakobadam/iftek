<?php

include("base_controller.php");

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$posts = array(
    array(
        id=>1,
        title=>"title 1",
        body=>"the body",
        user_id=>1,
        created_at=>"0000-00-00"
        ),
    array(
        id=>2,
        title=>"title 2",
        body=>"the body 2",
        user_id=>1,
        created_at=>"0000-00-00"
    ));

echo(render("posts.html", array(posts=>$posts)));

?>
