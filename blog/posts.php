<?php
require_once ("base_controller.php");
require_once ("models/post.php");
require_once ("dao/post_dao.php");

// Version uden paginering, dvs. alle posts vises på siden.
// if(is_logged_in()){
// $posts = Post_DAO::all();
// }
// else{
// $posts = Post_DAO::all("WHERE is_published = 1");
// }
// echo(render("posts.html", array('posts'=>$posts)));

$PAGE_SIZE = 2;
$LIMIT = $PAGE_SIZE + 1;

$offset = array_key_exists('offset', $_GET) ? $_GET['offset'] : null;

$posts = null;
$prev_posts = null;
$next_url = null;
$prev_url = null;

if($offset != null) {
    // FIXME: maybe clarify a bit...
    // Posts > offset sorteret stigende efter id
    $posts = Post_DAO::all("WHERE id >= $offset " . (is_logged_in() ? "" : "AND is_published = 1 ") . "ORDER BY id ASC LIMIT $LIMIT");
    // Posts < offset sorteret faldende efter id
    $prev_posts = Post_DAO::all("WHERE id < $offset " . (is_logged_in() ? "" : "AND is_published = 1 ") . "ORDER BY id DESC LIMIT $PAGE_SIZE");
} else {
    $posts = Post_DAO::all(is_logged_in() ? "WHERE is_published = 1 " : "" . "ORDER BY id ASC LIMIT $LIMIT");
}

// For at paginere hentes der PAGE_SIZE + 1 post. 
// Hvis det sidste element eksiterer skal der pagineres.
if(count($posts) > $PAGE_SIZE) {
    // det er det sidste element vi skal fortsætte fra
    $next_id = $posts[count($posts) - 1] -> id;
    $next_url = url_root() . "posts.php?offset=$next_id";
    $posts = array_slice($posts, 0, -1);
}

if($prev_posts != null) {
    // det er det første element vi skal fortsætte fra
    $prev_id = $prev_posts[0] -> id;
    $prev_url = url_root() . "posts.php?offset=$prev_id";
}

echo(render("posts.html", array('posts' => $posts, 'next_url' => $next_url, 'prev_url' => $prev_url)));?>
