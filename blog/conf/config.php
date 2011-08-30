<?php

// lokalt
if($_SERVER['SERVER_NAME'] == 'localhost'){
    define('DB_URL', 'localhost');
    define('DB_USER', 'root');
    define('DB_PWD', '');
    define('DB_NAME', 'blog');
}

// produktions miljøet
else{
    
    define('DB_URL', 'localhost');
    define('DB_USER', '');
    define('DB_PWD', '');
    define('DB_NAME', '');

    if(DB_USER == '' || DB_PWD == '' || DB_NAME == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal sætte navnet på database brugeren, koden til databasen, og navnet på den i conf/config.php');
        die();
    }
}
 
?>
