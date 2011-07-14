<?php

include_once ("abstract_dao.php");
include_once ("models/user.php");

/**
 * Funktioner til at hente, indsætte og opdatere brugere
 * i databasen.
 */
class User_DAO extends Abstract_DAO {
    
    /**
     * Hent en liste med alle brugere i databasen
     * 
     * @return array Alle brugerne i databasen
     */
    function get_all_users() {
        $result = parent::query("SELECT * FROM users");
        
        $users = array();

        while($row = mysql_fetch_array($result)){    
            $user = new User();
            $user->id = $row['id'];
            $user->created_at = $row['created_at'];
            $user->updated_at = $row['updated_at'];
            $user->email = $row['email'];
            $user->name = $row['name'];
            $user->password_hash = $row['password_hash'];
            $user->password_salt = $row['password_salt'];
            
            array_push($users, $user);
        }  
        
        return $users;
    }
    
}

?>
