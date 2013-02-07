<?php

require_once("controller.php");
require_once("db.php");

login_required();

// Kræv at titel og body er udfyldt når browseren POSTer
function validate_on_submit(){
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
       if(empty($_POST['title'])){
           echo "Titel skal udfyldes";
           die();
       }

       if(empty($_POST['body'])){
           echo "Body skal udfyldes";
           die();
       }
       
       return true;
    }
    return false;
}

if(validate_on_submit()){
    $title = $_POST['title'];
    $body = $_POST['body'];

    $is_published = 0;
    if(isset($_POST['is_published'])){
        $is_published = 1;
    }

    // NOTE: user_id sættes ved login, og er derfor altid tilstede her.
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (title, body, is_published, user_id, created_at) VALUES (?,?,?,?,NOW())";

    $stm = db_query($sql, array($title, $body, $is_published, $user_id));

    // Vis fejl hvis blog indlæg ikke kunne hentes
    if (!$stm) {
        die('Blog indlæg kunne ikke oprettes: ' . mysql_error());
    }

	flash('Sejt, nyt indlæg oprettet!');
	header('Location: posts.php');
	die();
}

echo(render("new_post_form.html"));

?>
