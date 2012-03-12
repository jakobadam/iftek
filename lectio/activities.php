<?php

require_once("base_controller.php");
require_once("facebook.php");
require_once("lectio.php");
      
if(array_key_exists('user', $_SESSION)){
    $user = $_SESSION['user'];
    
    if(!array_key_exists('lectio_id', $user) || !$user->lectio_id){
        flash('Du skal sætte dit lectio id!', 'errors');
        header('Location: index.php');
        die();
    }
    
    if(!array_key_exists('date', $_GET)){
        $date = time();
    }
    else{
        $date = intval($_GET['date']);
    }
    
    $yesterday = $date - 24 * 3600;
    $tomorrow = $date + 24 * 3600;
    
    $yesterday_url = 'activities.php?date=' . $yesterday;
    $tomorrow_url = 'activities.php?date=' . $tomorrow;
    $human_date = strftime('%d-%m-%Y', $date);

    // Parse lectio og print lektier og aflyste timer
    $activities = lectioGetActivities($user->lectio_id, $date);
    if(count($activities) == 0){
        flash('Der er ingen lektier idag!');
        header('Location: index.php');
        die();
    }

    $txt = '';
    foreach($activities as $activity){
        $txt = $txt . $activity['class'] . ' ' . $activity['time'] . ' ' . $activity['homework'] . '.';
    }
    
    echo(render("activities.html", array(
        'user'=>$user,
        'lectio_html'=>$txt, 
        'yesterday_url'=>$yesterday_url,
        'tomorrow_url'=>$tomorrow_url,
        'date'=>$date,
        'human_date'=>$human_date
    )));
}
else{
    flash('Du skal være logget ind!', 'error');
    header('Location: index.php');
}


?>