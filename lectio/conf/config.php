<?php
    
    define('APP_ID', '150320061738871');
    define('APP_SECRET', 'f41e741eed4c053550c5c7cfddfc54ce');
    define('APP_URL', 'http://skyen.iftek.dk/~jakob/lectio/index.php');
    
    // publish_stream: Adgang til at indsætte indhold i fb stream
    // offline_access: Adgang for evigt
    define('APP_PERMISSIONS', 'publish_stream,offline_access');

    if(APP_ID == '' || APP_SECRET == '' || APP_URL == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal sætte app id, app secret og app url i conf/config.php');
        die();
    }
?>
