<?php

require("base_controller.php");
require("facebook.php");

$login_url = fbGetLoginURL();

var_dump($_COOKIE);

echo(render("index.html", array('login_url'=>$login_url)));

?>
