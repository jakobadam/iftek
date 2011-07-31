<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        include("dao/user_dao.php");
            
        $users = User_DAO::get_all_users();

        if (count($users) == 0) {
            User_DAO::add_user("Jacob Mahler", "jacob@email.dk", "minkode");
            $users = User_DAO::get_all_users();
        }

        // Print alle posts
        foreach ($users as $user) {   
            echo $user->name . " " . $user->email . "<br />\n";
        }
        
        ?>
    </body>
</html>
