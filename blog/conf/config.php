<?php    
    define('DB_URL', 'localhost');
    define('DB_USER', 'dit_brugernavn'); // indsæt brugernavn mellem ''
    define('DB_PWD', 'dit_password'); // indsæt koden mellem ''
    define('DB_NAME', 'dit_brugernavn.blog'); // ændre brugernavn

    if(DB_USER == '' || DB_PWD == '' || DB_NAME == ''){
        header('Content-type: text/html; charset=utf-8');
        print('Husk du skal sætte navnet på database brugeren, koden til databasen, og navnet på den i conf/config.php');
        die();
    }
?>
