<?php

require_once("model.php");

class User extends Model{
    
    var $email;
    var $password;
    var $name;
    
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
     * Hent bruger med det givne id.
     * @param id
     */
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
