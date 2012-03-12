<?php
    
    define('APP_ID', '');
    define('APP_SECRET', '');
    
    // URLen som facebook giver os adgangs token via
    define('APP_REDIRECT_URL', '');
    define('DB_PATH', 'db');
    
    // publish_stream: Adgang til at indsætte indhold i fb stream
    // offline_access: Adgang for evigt
    define('APP_PERMISSIONS', 'publish_stream,offline_access');

    if(APP_ID == '' || APP_SECRET == '' || APP_REDIRECT_URL == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal sætte app id, app secret og app url i conf/config.php');
        die();
    }
?>
