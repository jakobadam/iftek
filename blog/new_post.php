<?php

include_once("base_controller.php");
include_once("forms/post_form.php");
include_once("models/post.php");

login_required();
$form = new PostForm($_POST);

if($form->validate_on_submit()){
	$post = new Post();
	$form->populate_obj($post);
	
    // FIXME: db
	// $db->add($post);
	// $db->save();
	flash('Sejt, nyt indlæg oprettet!');
	header('Location: ' . url_root() . 'posts.php');
	die();	
}

echo(render("new_post_form.html", array('form'=>$form)));

?>
