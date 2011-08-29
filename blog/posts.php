<?php

require_once ("base_controller.php");
require_once ("models/post.php");

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

$offset = null;
if(array_key_exists('offset', $_GET)){
    $offset = $_GET['offset'];
}

$posts = null;
$prev_posts = null;
$next_url = null;
$prev_url = null;
$published_only = !is_logged_in();

if($offset != null) { 

    // Vis kun ikke publiserede posts hvis brugeren er logget ind.
    
    // Det seneste blog posts har det højeste id.
    // Derfor hentes posts sorteret faldende (DESC af engelsk descending) efter id.
    
    // Eksempel: 6 posts, sidestørrelse 2
    // [[5,4],[3,2],[1,0]]

    // Antag at vi er på side 2 dvs. [3,2]
    // Der skal linkes til den næste side hvis der findes et tredje element, derfor hentes der (PAGE_SIZE + 1) elementer.
    
    $posts = Post::all_lte($offset, array('order'=>'DESC', 'published_only'=>$published_only, 'limit'=>$LIMIT));
 
    // Denne bruges til at vurderer om der findes en foregående side, og hvad URLen skal være.
    $prev_posts = Post::all_gt($offset, array('order'=>'ASC', 'published_only'=>$published_only, 'limit'=>$PAGE_SIZE));
    
} else {
    $posts = Post::all(array('order'=>'DESC', 'published_only'=>$published_only, 'limit'=>$LIMIT));
}

// For at paginere hentes der PAGE_SIZE + 1 posts. 
// Hvis det sidste element eksisterer skal der pagineres.
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

echo(render("posts.html", array('posts' => $posts, 'next_url' => $next_url, 'prev_url' => $prev_url)));

?>