<?php

/*
 * Poster dagens lektier til facebook feed for den bruger som er logget ind.
 */

require_once("base_controller.php");
require_once("facebook.php");
require_once("lectio.php");

function post(){
    if(!array_key_exists('date', $_GET)){
        $date = time();
    }
    else{
        $date = intval($_GET['date']);
    }
    
    $activities = lectioGetActivities($user->lectio_id, $date);
    
    if(count($activities) == 0){
        flash('Der er ingen lektier d.' . strftime('%d-%m-%Y', $date) . ':)');
        header('Location: index.php');
        die();
    }
    
    $txt = '';
    foreach($activities as $activity){
        $txt = $txt . $activity['class'] . ' ' . $activity['time'] . ' ' . $activity['homework'] . '.';
    }
    
    $response = json_decode(fbPost($user, $txt));
    
    if(array_key_exists('error', $response)){
        flash($response->error, 'errors');
        die();    
    }
    else{
        flash('Lektier blev postet til facebook');
    }
}

if(array_key_exists('user', $_SESSION)){
    $user = $_SESSION['user'];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        post();    
        // send brugeren tilbage til hvor han kom fra
        header('Location: '. $_SERVER['HTTP_REFERER']);
        die();    
    }
    else{
        echo('Du kan kun poste ved at klikke på formen');
        die();
    }
}
else{
    flash('Du skal være logget ind!', 'error');
    header('Location: index.php');
}

?>