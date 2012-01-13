<?php
    
    define('APP_ID', '');
    define('APP_SECRET', '');
    define('APP_URL', '');

    if(APP_ID == '' || APP_SECRET == '' || APP_URL == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal sætte app id, app secret og app url i conf/config.php');
        die();
    }
 
?>
