<?php

require_once("controller.php");

session_destroy();
header('Location: posts.php');
die();

?>
