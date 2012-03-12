<?php

/**
 * Et meget simpelt datalag. 
 * 
 * Brugere gemmes som filer på disken. 
 * Filens navn er facebook id'et. 
 */
require_once('conf/config.php');


/**
 * Hent en enkelt bruger.
 */
function getUser($id){
    $user = null;
    $path = DB_PATH . '/' . $id;
    /* @ ignorerer advarsler */
    $contents = @file_get_contents($path); 
    if($contents != null){
        $user = json_decode($contents);
    }
    return $user;
}

/**
 * Hent alle brugere.
 */
function getUsers(){
    $users = array();
    $user_files = scandir(DB_PATH);
    foreach ($user_files as $file) {
        array_push($users, getUser($file));
    }
    return $users;
}

/**
 * Gem brugeren lokalt.
 */
function saveUser($user){
    $file_name = $user->id;
    $path = DB_PATH . '/' . $file_name;
    $fh = fopen($path, 'w');
    
    if(!$fh){
        echo("Could not open file: $path");
        die();
    }
    fwrite($fh, json_encode($user));
    fclose($fh);
}


?>