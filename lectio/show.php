<?php

require_once("base_controller.php");
require_once("facebook.php");
require_once("lectio_parser.php");


if(array_key_exists('fbUser', $_SESSION)){
    $user = $_SESSION['fbUser'];
    // Dato
    $actual_date = mktime(0,0,0,date("m"),date("d")+4,date("Y"));

    // Parse lectio og print lektier og aflyste timer
    $lectio_html = parse_lectio($actual_date, $user->lectio_id);
    echo(render("show.html", array('lectio_html'=>$lectio_html)));
}
else{
    flash("Du skal være logget ind!", "error");
    echo(render("base.html"));
    die();
}


?>