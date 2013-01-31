<?php

require_once "base_controller.php";
require_once "forms/post_form.php";
require_once("models/db.php");

login_required();

$id = intval($_GET['id']);

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

function clean(){
    $id = intval($_GET['id']);
    $is_published = 0;
    if(isset($_POST['is_published'])){
        $_POST['is_published'] = 1;
    }
    else{
        $_POST['is_published'] = 0;
    }

    // NOTE: user_id sættes ved login, og er derfor altid tilstede her.
    $_POST['user_id'] = $_SESSION['user_id'];
    $_POST['id'] = $id;
}


$post = db_query("SELECT * FROM posts WHERE id = :id", array('id'=>$id));

if(!$post){
    flash_error('Det post findes ikke!');
    die();
}

if(validate_on_submit()){
    clean();
    $sql = "UPDATE posts SET title = :title, body = :body, is_published = :is_published, user_id = :user_id, updated_at = NOW() WHERE id = :id";
    var_dump($_POST);
    db_query($sql, $_POST);

	flash('Ændringer gemt!');
	header('Location: posts.php');
	die();
}

echo(render("edit_post_form.html", array('post'=>$post)));

?>
