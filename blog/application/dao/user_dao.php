<?php

include_once ("abstract_dao.php");
include_once ("models/user.php");

/**
 * Funktioner til at hente, indsætte og opdatere brugere
 * i databasen.
 */
class User_DAO extends Abstract_DAO {
    
    /**
     * Opret et nyt blog indlæg
     * @param type $title Titlen på det nye blogindlæg
     * @param type $body Blog indlægets indhold / tekst
     * @param type $is_published True hvis indlæget skal være synligt og false hvis skjult
     * @param type $user_id ID'et på den bruger, der opretter indlæget
     */
    
    
    
    /**
     * Opret en nyt bruger i systemet
     * @param type $name Navnet på brugeren
     * @param type $email Email/brugernavn til at logge ind med
     * @param type $password Kodeord til at logge ind med
     */
    static function add_user($name, $email, $password) {
        $password_hash = $password; // TODO
        $password_salt = $password; // TODO
        
        // Opret ny bruger i databasen
        $result = parent::query("INSERT INTO users Values('', '" . $email . "', '" . $password_hash . "', '" . $password_salt . "', '" . $name . "', NOW(), NOW())");
        
        // Vis fejl hvis brugeren ikke kunne oprettes
        if (!$result) {
            die('Brugeren kunne ikke oprettes: ' . mysql_error());
        }
    }
    
    /**
     * Hent en liste med alle brugere i databasen
     * 
     * @return array Alle brugerne i databasen
     */
    static function get_all_users() {
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
