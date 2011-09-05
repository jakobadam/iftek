<?php

require_once("conf/config.php");
/**
 * Generisk Model klasse.
 * 
 * Indeholder genbrugelig funktionalitet for alle modeller.
 */
class Model{
		
    var $id;
    var $created_at;
    var $updated_at;
	
    /**
     * Konstruerer en konkret objekt og sætter variablerne på objektet til 
     * dem der er givet i arrayet. 
     */
	function __construct($kwargs=array()){
		foreach($kwargs as $key => $value){
			$this->$key = $value;
		}
	}
    
   /** Funktioner og variabler der er placeret på klassen er herunder.*/
    
   static $conn;
   
   /**
    * Åben en forbindelse til databasen.
    * 
    * @return type connection til databasen
    */
   static function get_connection() {
           
       if(self::$conn != null){
           return self::$conn;
       }
       
       try{
            // Opret forbindelse til database-serveren
            self::$conn = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_URL, DB_USER, DB_PWD);
            self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
            return self::$conn;
       }
       catch(PDOException $e){
           // Hvis det mislykkedes giv brugeren en fejl
           die('Kunne ikke oprette forbindelse til database-serveren: ' . $e);           
       }
       
    }

    /**
     * Udfør en query forspørgsel på database-serveren
     * 
     * @param string $query Forspørgsel til databasen
     * @return type Resultat fra MySQL
     */
    static function query($query) {
        
        // Åben forbindelse til databasen hvis der ikke allerede findes en
        $conn = self::get_connection();
        $stm = $conn->query($query);
        
        // Hent som ordbog
        $stm->setFetchMode(PDO::FETCH_ASSOC);  
        return $stm;
    }
       
   /**
     * Opret prepared statement. Dvs. et SQL udtryk med kroge hvorpå der kan hænges data.
     * Største fordel ved prepared stms. er at de er sikre mod SQL injections. 
     * 
     * @param query der skal forberedes.
     */
    static function prepare($query){
        $conn = self::get_connection();
        $stm = $conn->prepare($query);
        
        // Hent som ordbog
        $stm->setFetchMode(PDO::FETCH_ASSOC);  
        return $stm;
    }
    
    /**
     * Returnerer id på det sidste der blev indsat i databasen.
     */
    static function lastInsertId(){
        return self::$conn->lastInsertId();
    }
    
}
?>
