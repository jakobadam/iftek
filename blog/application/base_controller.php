<?php

require_once '/usr/share/php/Twig/Autoloader.php';

Twig_Autoloader::register();

session_start();

function populate_context($context){
	if(array_key_exists('email', $_SESSION)){
	 	// TODO: get user by email	
	 	$user = array(email=>'foo@example.com');
	 	$context['user'] = $user;
	}
	return $context;
}

function login_required(){
	if(!array_key_exists('email', $_SESSION)){
		// TODO: redirect to where the user is coming from.
	 	echo(render('login_form.html', array(errors=>array('Hey, du skal være logget ind!'))));
	 	die();
	}	
}

function render($template, $context=array()){
    $context = populate_context($context);
    $loader = new Twig_Loader_Filesystem('views');
    //$twig = new Twig_Environment($loader, array('cache' => 'views_cache'));  
    $twig = new Twig_Environment($loader);  
    $template = $twig->loadTemplate($template);
    return $template->render($context);
}
?>
