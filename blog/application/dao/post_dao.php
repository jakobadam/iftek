<?php

include_once ("abstract_dao.php");
include_once ("models/post.php");

/**
 * Funktioner til at hente, indsætte og opdatere blog posts
 * i databasen.
 */
class Post_DAO extends Abstract_DAO {
    
    /**
     * Hent en liste med alle blog posts i databasen
     * 
     * @return array Alle posts i databasen
     */
    function get_all_posts() {
        $result = parent::query("SELECT * FROM posts");
        
        $posts = array();

        while($row = mysql_fetch_array($result)) {
            
            // Lav et Post object ud af MySQL row resultatet
            $post = $this->build_post($row);
            
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
    function get_post_by_id($id) {
        $result = parent::query("SELECT * FROM posts WHERE id = " . $id . " LIMIT 1");
        
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
    private function build_post($row) {
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