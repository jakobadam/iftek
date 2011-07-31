<?php

include_once("base_controller.php");
include_once("forms/post_form.php");
include_once("models/post.php");

login_required();
//$post = get_object_or_404();
$post = array();
$form = new PostForm($_POST);

if($form->validate_on_submit()){
	$form->populate_obj($post);
	
	// $db->add($post);
	// $db->save();
	// FIXME: include a message here
	flash('Ændringer gemt!');
	header('Location: /posts.php');
	die();	
}

echo(render("edit_post_form.html", array(form=>$form)));

?>
