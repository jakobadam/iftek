<?php

require_once("controller.php");
require_once("db.php");

$PAGE_SIZE = 10;
$LIMIT = $PAGE_SIZE + 1;

$offset = null;
if(isset($_GET['offset'])){
    $offset = $_GET['offset'];
}

$posts = null;
$prev_posts = null;
$next_url = null;
$prev_url = null;
// Vis kun ikke publiserede posts hvis brugeren er logget ind.
$show_published_only = !is_logged_in();

if($offset == null) {
    $sql = "SELECT * from posts";
    if($show_published_only){
        $sql = $sql . " WHERE is_published = 1";
    }
    $sql = $sql . " ORDER BY id DESC LIMIT " . $LIMIT;
    $posts = db_query($sql)->fetchAll();
} else {

    // Det seneste blog posts har det højeste id.
    // Derfor hentes posts sorteret faldende (DESC af engelsk descending) efter id.

    // Eksempel: 6 posts, sidestørrelse 2
    // [[5,4],[3,2],[1,0]]

    // Antag at vi er på side 2 dvs. [3,2]
    // Der skal linkes til den næste side hvis der findes et tredje element, derfor hentes der (PAGE_SIZE + 1) elementer.
    $sql = "SELECT * FROM posts WHERE ";
    if($show_published_only){
        $sql = $sql . "is_published = 1 AND ";
    }
    $sql = $sql . "id <= :id ORDER BY id DESC LIMIT " . $LIMIT;
    $posts = db_query($sql, array('id'=>$offset))->fetchAll();

    // Denne bruges til at vurderer om der findes en foregående side,
    // og hvad URLen skal være.
    $sql = "SELECT * from posts WHERE ";
    if($show_published_only){
        $sql = $sql . "is_published = 1 AND ";
    }
    $sql = $sql . "id > :id ORDER BY id ASC LIMIT " . $PAGE_SIZE;
    $prev_posts = db_query($sql, array('id'=>$offset))->fetchAll();
}

// For at paginere hentes der PAGE_SIZE + 1 posts.
// Hvis det sidste element eksisterer skal der pagineres.
if(count($posts) > $PAGE_SIZE) {
    // det er det sidste element vi skal fortsætte fra
    $next_post = $posts[count($posts) - 1];
    $next_id = $next_post['id'];
    $next_url = "posts.php?offset=$next_id";

    // fjern det sidste (+1) element igen
    $posts = array_slice($posts, 0, -1);
}

if($prev_posts != null) {
    // posts er sorteret stigende her
    // fx hvis offset er 3 så hentes [[4,5]]

    // det er det sidste element 5 vi skal bruge 
    $prev_post = $prev_posts[count($prev_posts) - 1];
    $prev_id = $prev_post['id'];
    $prev_url = "posts.php?offset=$prev_id";
}

echo(render("posts.html", array('posts' => $posts, 'next_url' => $next_url, 'prev_url' => $prev_url)));

?>