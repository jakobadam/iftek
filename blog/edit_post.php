<?php

require_once "base_controller.php";
require_once "forms/post_form.php";
require_once "models/post.php";
require_once "dao/post_dao.php";

login_required();

$post = Post_DAO::get_post_by_id($_GET['id']);
if(!$post){
    flash_error('Det post findes ikke!');
    die();    
} 


$form = new PostForm($_POST, $post);

if($form->validate_on_submit()){
	$form->populate_obj($post);
    Post_DAO::update_post($post);
	flash('Ændringer gemt!');
	header('Location: ' . url_root() . 'posts.php');
	die();	
}

echo(render("edit_post_form.html", array('form'=>$form)));

?>
