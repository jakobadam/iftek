<?php

require_once("base_controller.php");
require_once("facebook.php");
require_once("lectio.php");

if(array_key_exists('fbUser', $_SESSION)){
    $user = $_SESSION['fbUser'];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $activities = lectioGetActivities($user->lectio_id);
        
        $txt = '';
        foreach($activities as $activity){
            $txt = $txt . $activity['class'] . ' ' . $activity['time'] . ' ' . $activity['homework'] . '.';
        }
        
        $response = json_decode(fbPost($user, $txt));
        
        if(array_key_exists('error', $response)){
            flash($response->error, 'error');
            die();    
        }
        else{
            flash('Lektier blev postet til facebook');
        }
        header('Location: index.php');
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