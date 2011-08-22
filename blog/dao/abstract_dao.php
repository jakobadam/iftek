<?php

require_once("conf/conf.php");
require_once("models/user.php");

/**
 * Generel kode til at tilgå MySQL databasen for
 * alle DAO klasser. DAO står for Data Access Object
 * og er det lag i projektet, der står for at hente data
 * fra databasen og konvertere det til modeller, vi kan
 * benytte fra PHP.
 */
class Abstract_DAO {

    static $conn;
   /**
    * Åben en forbindelse til databasen.
    * 
    * @return type connection til databasen
    */
   static function open_connection() {
           
       if(self::$conn != null){
           return self::$conn;
       }
       // Opret forbindelse til database-serveren
       
       try{
            //$conn = new PDO('mysql:dbname=' . Conf::$db_name . ';host=' . Conf::$db_url, Conf::$db_user, Conf::$db_pwd);  
            self::$conn = new PDO('mysql:dbname=blog;host=localhost', Conf::$db_user, Conf::$db_pwd);
            self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
            return self::$conn;
       }
       catch(PDOException $e){
           // Hvis det mislykkedes giv brugeren en fejl
           die('Kunne ikke oprette forbindelse til database-serveren: ' . $e);           
       }
    
       // $link = mysql_connect(Conf::$db_url, Conf::$db_user, Conf::$db_pwd);
//       
       // Overvej dette kald i stedet, så genbruges connections
       //mysql_pconnect()
 
       
       // Vælg blog databasen som standard database
       // $db = mysql_select_db(Conf::$db_name, $link);
//         
       // Hvis det mislykkedes giv brugeren en fejl
       // if (!$db) {
           // die ('Kan ikke benytte den valgte database: ' . mysql_error());
       // }
       
    }
    
    /**
     * Luk forbindelsen til databasen
     */
    static function close_connection() {
        // FIXME: pass by value / reference?
        self::$conn = null;
    }
     
    /**
     * Udfør en query forspørgsel på database-serveren
     * 
     * @param string $query Forspørgsel til databasen
     * @return type Resultat fra MySQL
     */
    static function query($query) {
        
        // Åben forbindelse til databasen hvis der ikke allerede findes en
        $conn = self::open_connection();
        $stm = $conn->query($query);
        // Hent som ordbog
        $stm->setFetchMode(PDO::FETCH_ASSOC);  
        return $stm;
    }
    
    /**
     * Opret prepared stm.
     * 
     * @param query der skal forberedes.
     */
    static function prepare($query){
        $conn = self::open_connection();
        $stm = $conn->prepare($query);
        // Hent som ordbog
        $stm->setFetchMode(PDO::FETCH_ASSOC);  
        return $stm;
    }
    
    static function lastInsertId(){
        return self::$conn->lastInsertId();
    }
}

?>
