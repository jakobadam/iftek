<?php

require_once 'libs/Twig/Autoloader.php';

Twig_Autoloader::register();

session_start();

function flash($msg, $type='messages'){
	if(!array_key_exists($type, $_SESSION)){
		$_SESSION[$type] = array();
	}
	array_push($_SESSION[$type], $msg);
}

function flash_error($msg){
	flash($msg, 'errors');
}

function get_flashes($type='messages'){
	if(array_key_exists($type, $_SESSION)){
		$flashes = $_SESSION[$type]; 
		$_SESSION[$type] = array();
		return $flashes;
	}
	return NULL;	
}

function get_error_flashes(){
	return get_flashes('errors');
}

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
	$context['messages'] = get_flashes();
	$context['errors'] = get_error_flashes();
	return $context;
}

/**
 * Omdireger til login hvis bruger ikke er logget ind.
 */
function login_required(){
	if(!array_key_exists('email', $_SESSION)){
		// TODO: redirect to where the user is coming from.
		flash_error('Hey, du skal være logget ind!');
	 	echo(render('login_form.html'));
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
