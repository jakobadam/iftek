<?php

include_once("base_controller.php");

session_destroy();
header('Location: /');
die();

?>
