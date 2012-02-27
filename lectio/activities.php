<?php

require_once("base_controller.php");
require_once("facebook.php");
require_once("lectio.php");

if(array_key_exists('fbUser', $_SESSION)){
    $user = $_SESSION['fbUser'];
    
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

    $txt = '';
    foreach($activities as $activity){
        $txt = $txt . $activity['class'] . ' ' . $activity['time'] . ' ' . $activity['homework'] . '.';
    }
    
    echo(render("activities.html", array(
        'lectio_html'=>$txt, 
        'yesterday_url'=>$yesterday_url,
        'tomorrow_url'=>$tomorrow_url,
        'human_date'=>$human_date
    )));
}
else{
    flash('Du skal være logget ind!', 'error');
    header('Location: index.php');
}


?>