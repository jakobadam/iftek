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
    static function add($user) {  
        // Opret ny bruger i databasen
        $stm = self::prepare("INSERT INTO users (email, name, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        
        $success = $stm->execute(array(
            $user->email, 
            $user->name, 
            $user->password));
        
        // Vis fejl hvis brugeren ikke kunne oprettes
        if (!$success) {
            die('Brugeren kunne ikke oprettes: ' . mysql_error());
        }

        return self::lastInsertId();
    }
    
    /**
     * Hent en liste med alle brugere fra databasen
     * 
     * @return array Alle brugerne i databasen
     */
    static function get_all_users() {
        $stm = parent::query("SELECT * FROM users");
        
        $users = array();
        
        while($row = $stm->fetch()){    
            $user = new User($row);            
            array_push($users, $user);
        }  
        
        return $users;
    }
    
        /**
     * Hent en liste med alle blog posts i databasen
     * 
     * @return array Alle posts i databasen
     */
    static function get_by_email_and_password($email, $password) {
        $stm = parent::prepare("SELECT * FROM users WHERE email = ? and password = ? LIMIT 1");
        
        $stm->execute(array($email, $password));
        // Hent resultat
        $row = $stm->fetch();
        
        // Tjek om resultatet blev fundet
        if (!$row){
            return null;            
        }
        
        return new User($row);
    }
    
    // FIXME: abstract into abstract dao
    static function get($id) {
        $stm = parent::prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        
        // udskift markøren - ? - med værdien
        $stm->execute(array($id));
        
        // Hent resultat
        $row = $stm->fetch();
        
        // Tjek om resultatet blev fundet
        if (!$row){
            return null;            
        }
        
        return new User($row);
    }
    
}

?>
