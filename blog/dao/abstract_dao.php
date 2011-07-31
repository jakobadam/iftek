<?php

include("conf/conf.php");
include("models/user.php");

/**
 * Generel kode til at tilgå MySQL databasen for
 * alle DAO klasser. DAO står for Data Access Object
 * og er det lag i projektet, der står for at hente data
 * fra databasen og konvertere det til modeller, vi kan
 * benytte fra PHP.
 */
class Abstract_DAO {
   
   /**
    * Åben en forbindelse til databasen.
    * 
    * @return type Link til databasen
    */
   static function open_connection() {
       
       // Opret forbindelse til database-serveren
       $link = mysql_connect(Conf::$db_url, Conf::$db_user, Conf::$db_pwd);
      
       // Overvej dette kald i stedet, så genbruges connections
       //mysql_pconnect()
       
       // Hvis det mislykkedes giv brugeren en fejl
       if (!$link) {
           die('Kunne ikke oprette forbindelse til database-serveren: ' . mysql_error());
       }
       
       // Vælg blog databasen som standard database
       $db = mysql_select_db(Conf::$db_name, $link);
        
       // Hvis det mislykkedes giv brugeren en fejl
       if (!$db) {
           die ('Kan ikke benytte den valgte database: ' . mysql_error());
       }
       
       return $link;
    }
    
    /**
     * Luk forbindelsen til databasen
     */
    static function close_connection($connection) {
        mysql_close($connection);
    }
     
    /**
     * Udfør en query forspørgsel på database-serveren
     * 
     * @param string $query Forspørgsel til databasen
     * @return type Resultat fra MySQL
     */
    static function query($query) {
        
        // Åben forbindelse til databasen hvis der ikke allerede findes en
        self::open_connection();
        
        // Udfør forspørgsel på databasen
        $result = mysql_query($query);
        
        return $result;
    }
}

?>
