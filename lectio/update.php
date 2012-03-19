<?php

/**
 * Opdaterings job. 
 */

require_once('conf/config.php');
require_once('models/users.php');
require_once('lectio.php');
require_once('facebook.php');

$date = time();

// ikke brugt nu, men kunne bruges til at hente lektier for
// i morgen.
$tomorrow = $date + 24 * 3600;

$user = getUser($_GET['user_id']);
$access_token = $_GET['access_token'];

if($access_token != $user->access_token){
    echo('Forkert access token for det bruger id!');
    die();
}

$activities = lectioGetActivities($user->lectio_id, $date);

if(count($activities) > 0){
    $txt = '';
    foreach($activities as $activity){
        if($activity['status'] == 'aflyst'){
            // post til facebook
            $response = fbPost($user, $activity['class'] . ' er aflyst');
            // udskriv resultatet
            echo($response);    
        }
    }
}
else{
    echo('Ingen lektier!');
}
?>