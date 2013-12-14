<?php    
    define('DB_URL', 'localhost');
    define('DB_USER', 'dit_brugernavn');
    define('DB_PWD', 'dit_password');
    define('DB_NAME', 'dit_brugernavn.blog');

    if(DB_USER == '' || DB_PWD == '' || DB_NAME == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal angive navnet paa database brugeren, koden til databasen, og navnet paa den i conf/config.php');
        die();
    }
?>
