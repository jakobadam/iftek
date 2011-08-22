<?php

require_once("abstract_dao.php");
require_once("models/post.php");

/**
 * Funktioner til at hente, indsætte og opdatere blog posts
 * i databasen.
 */
class Post_DAO extends Abstract_DAO {
    
    /**
     * Opret et nyt blog indlæg
     * @param type $title Titlen på det nye blogindlæg
     * @param type $body Blog indlægets indhold / tekst
     * @param type $is_published True hvis indlæget skal være synligt og false hvis skjult
     * @param type $user_id ID'et på den bruger, der opretter indlæget
     */
    static function add_post($title, $body, $is_published, $user_id) {
        
        // Insæt nyt indlæg
        $result = parent::query("INSERT INTO posts Values('', NOW(), NOW(), '" . $title . "','" . $body . "'," . $is_published . "," . $user_id . ")");
        
        // Vis fejl hvis indlæg ikke kunne oprettes
        if (!$result) {
            die('Blog post kunne ikke oprettes: ' . mysql_error());
        }
    }
    
    /**
     * Opret nyt post i databasen
     * @param post $post Objekt der skal gemmes i db'en.
     */
    static function add($post) {
        
        // Opdater post ud fra det opdateret post objekt
        $stm = parent::query("INSERT INTO posts 
            (title, body, is_published, user_id, created_at) VALUES (
            '$post->title',
            '$post->body', 
            $post->is_published, 
            '$post->user_id',
            NOW())");
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$stm) {
            die('Blog indlæg kunne ikke oprettes: ' . mysql_error());
        }
    }
    
    /**
     * Opdater et allerede eksisterende post objekt
     * @param type $post Post objekt der skal opdateres
     */
    static function update($post) {
        // FIXME: sql injection
        // Opdater post ud fra det opdateret post objekt
        $stm = parent::query("UPDATE posts 
            SET title='" . $post->title . "', 
            body='" . $post->body . "', 
            is_published=" . $post->is_published . ", 
            user_id=" . $post->user_id . ",
            updated_at=NOW() 
            WHERE id = " . $post->id);
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$stm) {
            die('Blog indlæg kunne ikke opdateres: ' . mysql_error());
        }
    }
    
    /**
     * Hent en liste med alle blog posts i databasen
     * 
     * @param published_filter Kun publiseretede artikeler.
     * @return array Alle posts i databasen
     */
    static function all($filter="") {
        
        $sql = "SELECT * FROM posts $filter ORDER BY created_at DESC";
        $stm = parent::query($sql);
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$stm) {
            die('Blog indlæg kunne ikke hentes: ' . mysql_error());
        }
        
        $posts = array();

        while($row = $stm->fetch()) {
            
            // Lav et Post object ud af MySQL row resultatet
            $post = new Post($row);
            
            // Tilføj det nye post til listen
            array_push($posts, $post);
        }  
        
        return $posts;
    }
    
    /**
     * Hent et bestemt blog post fra databasen.
     * 
     * @param id
     * @return Post
     */
    static function get($id) {
        $stm = parent::prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
        
        // udskift markøren - ? - med værdien
        $stm->execute(array($id));
        
        // Hent resultat
        $row = $stm->fetch();
        
        // Tjek om resultatet blev fundet
        if (!$row){
            return null;            
        }
        
        return new Post($row);
    }
    
}

?>