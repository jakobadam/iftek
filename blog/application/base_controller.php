<?php

require_once '/usr/share/php/Twig/Autoloader.php';

Twig_Autoloader::register();

session_start();

/**
 * Tilføjer værdier til konteksten - der sendes med når html renderes - 
 * som vi har brug for hver gang.
 */
function populate_context($context){
	if(array_key_exists('email', $_SESSION)){
	 	// TODO: get user by email	
	 	$user = array(email=>'foo@example.com');
	 	$context['user'] = $user;
	}
	if(array_key_exists('message', $_GET)){
		if(!array_key_exists('messages', $context)){
			$context['messages'] = array();
		}
		array_push($context['messages'], urldecode($_GET['message']));
	}
	if(array_key_exists('error', $_GET)){
		if(!array_key_exists('errors', $context)){
			$context['errors'] = array();
		}
		array_push($context['errors'], urldecode($_GET['error']));
	}
	return $context;
}

/**
 * Omdireger til login hvis bruger ikke er logget ind.
 */
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
	print_r($context);
    return $template->render($context);
}
?>
