<?php

require_once('libs/Twig/Autoloader.php');
require_once('models/db.php');

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
	if(is_logged_in()){
	 	$sql = "SELECT * FROM users WHERE id = ?";
		$user = db_query($sql, array(intval($_SESSION['user_id'])));
	 	
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
	if(!is_logged_in()){
		// TODO: redirect to where the user is coming from.
		flash_error('Hey, du skal være logget ind!');
	 	echo(render('login_form.html'));
	 	die();
	}	
}

function is_logged_in(){
    if(array_key_exists('user_email', $_SESSION) && array_key_exists('user_id', $_SESSION)){
        $sql = "SELECT * FROM users WHERE id = ?";
		$user = db_query($sql, array(intval($_SESSION['user_id'])));
		
        if($user && $user['email'] == $_SESSION['user_email']){
            return true;
        }
    }
    return false;
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
