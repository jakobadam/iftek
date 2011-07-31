<?php

include_once ("abstract_dao.php");
include_once ("models/post.php");

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
     * Opdater et allerede eksisterende post objekt
     * @param type $post Post objekt der skal opdateres
     */
    static function update_post($post) {
        
        // Opdater post ud fra det opdateret post objekt
        $result = parent::query("UPDATE posts 
            SET title='" . $post->title . "', 
            body='" . $post->body . "', 
            is_published=" . $post->is_published . ", 
            user_id=" . $post->user_id . ",
            updated_at=NOW() 
            WHERE id = " . $post->id);
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$result) {
            die('Blog indlæg kunne ikke opdateres: ' . mysql_error());
        }
    }
    
    /**
     * Hent en liste med alle blog posts i databasen
     * 
     * @return array Alle posts i databasen
     */
    static function get_all_posts() {
        $result = parent::query("SELECT * FROM posts");
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$result) {
            die('Blog indlæg kunne ikke hentes: ' . mysql_error());
        }
        
        $posts = array();

        while($row = mysql_fetch_array($result)) {
            
            // Lav et Post object ud af MySQL row resultatet
            $post = self::build_post($row);
            
            // Tilføj det nye post til listen
            array_push($posts, $post);
        }  
        
        return $posts;
    }
    
    /**
     * Hent en liste med alle blog posts i databasen
     * 
     * @return array Alle posts i databasen
     */
    static function get_post_by_id($id) {
        $result = parent::query("SELECT * FROM posts WHERE id = " . $id . " LIMIT 1");
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$result) {
            die('Blog indlæg kunne ikke hentes: ' . mysql_error());
        }
        
        // Hent resultat
        $row = mysql_fetch_row($result);
        
        // Tjek om resultatet blev fundet
        if (cout($row) == 0)
            return null;
        
        // Byg Post object hvis resultat fandtes
        return $this->build_post($row);
    }
    
    /**
     * Konverter en MySQL row til en Post model
     * 
     * @param type $row MySQL row
     * @return Post model
     */
    private static function build_post($row) {
        $post = new Post();
        $post->id = $row['id'];
        $post->created_at = $row['created_at'];
        $post->updated_at = $row['updated_at'];
        $post->title = $row['title'];
        $post->body = $row['body'];
        $post->is_published = $row['is_published'];
        $post->user_id = $row['user_id'];
        
        return $post;
    }
}

?>