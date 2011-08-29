<?php

require_once "base_controller.php";
require_once "forms/post_form.php";
require_once "models/post.php";

login_required();

$post = Post::get(intval($_GET['id']));
if(!$post){
    flash_error('Det post findes ikke!');
    die();    
} 


$form = new PostForm($_POST, $post);

if($form->validate_on_submit()){
	$form->populate_obj($post);
    Post::update($post);
	flash('Ændringer gemt!');
	header('Location: posts.php');
	die();	
}

echo(render("edit_post_form.html", array('form'=>$form)));

?>
