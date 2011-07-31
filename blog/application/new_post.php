<?php

include_once("base_controller.php");
include_once("forms/post_form.php");
include_once("models/post.php");

login_required();
$form = new PostForm($_POST);

if($form->validate_on_submit()){
	$post = new Post();
	$form->populate_obj($post);
	
	// $db->add($post);
	// $db->save();
	// FIXME: include a message here
	flash('Sejt, nyt indlæg oprettet!');
	header('Location: /posts.php');
	die();	
}

echo(render("new_post_form.html", array(form=>$form)));

?>
