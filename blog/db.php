<?php

require_once("conf/config.php");


$conn;

/**
 * Åben en forbindelse til databasen.
 *
 * @return type connection til databasen
 */
function get_connection() {
	
	global $conn;

    if(isset($conn)){
        return $conn;
    }

    try{
        // Opret forbindelse til database-serveren
        $conn = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_URL, DB_USER, DB_PWD);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        return $conn;
    }
    catch(PDOException $e){
        // Hvis det mislykkedes giv brugeren en fejl
        die('Kunne ikke oprette forbindelse til database-serveren: ' . $e);
    }
}

/**
 * Opret prepared statement. Dvs. et SQL udtryk med kroge hvorpå der kan hænges data.
 * Største fordel ved prepared stms. er at de er sikre mod SQL injections.
 *
 * @param query der skal forberedes.
 * @param $data
 */
function db_query($query, $data=array()){
    $conn = get_connection();
    $stm = $conn->prepare($query);

    // Hent som ordbog
    $stm->setFetchMode(PDO::FETCH_ASSOC);
    $stm->execute($data);

    // PDO stm kan der itereres med.
    return $stm;
}

/** 
 * Hent en enkelt række fra databaseb.
 */
function db_query_get($query, $data=array()){
	$stm = db_query($query, $data);
	return $stm->fetch();
}

/**
 * Returnerer id på det sidste der blev indsat i databasen.
 */
function db_last_insert_id(){
    $conn = get_connection();
    return $conn->lastInsertId();
}

?>
