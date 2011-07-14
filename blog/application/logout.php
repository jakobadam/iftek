<?php

include("base_controller.php");

session_destroy();
header('Location: /');
die();

?>
