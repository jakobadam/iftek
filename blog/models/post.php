<?php

require_once("model.php");
require_once("base_controller.php");

class Post extends Model{
    
    static $table_name = "posts";
    
    var $body;
    var $title;
    var $is_published;
    var $user_id;
    
    /* cachet user objekt */
    var $_user;
    
    function url(){
        return 'post.php?id=' . $this->id;
    }
    
    function edit_url(){
        return 'edit_post.php?id=' . $this->id;
    }
    
    function excerpt(){
        // FIXME: mere logik her dette ødelægger html'en!
        return $this->body;
    }
    
    function user(){
        if($this->_user == null){
            $this->_user = User::get($this->user_id);
        }
        return $this->_user;
    }
    
    /** Funktioner placeret på klassen - dvs. static - herunder */
    
    /**
     * Opret ny post i databasen.
     * 
     * @param post $post Post der skal gemmes i db'en.
     */
    static function add($post) {
        
        // Opdater post ud fra det opdateret post objekt
        $stm = self::prepare("INSERT INTO posts (title, body, is_published, user_id, created_at) VALUES (?,?,?,?,NOW())");
            
        $stm->execute(array($post->title, $post->body, $post->is_published, $post->user_id));
        
        // Vis fejl hvis blog indlæg ikke kunne hentes
        if (!$stm) {
            die('Blog indlæg kunne ikke oprettes: ' . mysql_error());
        }
        
        return self::lastInsertId();
    }
    
    /**
     * Hent et bestemt blog post fra databasen.
     * 
     * @param id
     * @return Post
     */
    static function get($id) {
        $stm = self::prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
        
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
    
    /**
     * Opdater et allerede eksisterende post objekt
     * @param type $post Post objekt der skal opdateres
     */
    static function update($post) {

        // Opdater post ud fra det opdateret post objekt
        $stm = parent::prepare("UPDATE posts SET 
            title = ?, body = ?, is_published = ?, user_id = ?, updated_at = NOW()
            WHERE id = ?");
        $stm->execute(array($post->title, $post->body, $post->is_published, $post->user_id, $post->id));
        
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
    static function all($args=array()) {
        $filter = array_key_exists('filter', $args) ? $args['filter'] : '';    
        $order = array_key_exists('order', $args) ? $args['order'] : 'ASC';
        $limit = array_key_exists('limit', $args) ? $args['limit'] : 1000;
        $published_only = array_key_exists('published_only', $args) ? $args['published_only'] : false;
        
        $sql = "SELECT * FROM posts $filter"; 
        if($published_only){
            if($filter != ''){
                $sql = $sql . " AND is_published = 1";            
            }
            else{
                $sql = $sql . " WHERE is_published = 1";            
            }
        }
        $sql = $sql . " ORDER BY id $order LIMIT $limit";
        $stm = Post::query($sql);

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
     * Alle posts med id lte (<=) $offset.
     */
    static function all_lte($offset, $args=array()){
        $args['filter'] = "WHERE id <= " . intval($offset);   
        return self::all($args);
    }
    
    /**
     * Alle posts med id gt (>) $offset.
     */
    static function all_gt($offset, $args=array()){
        $args['filter'] = "WHERE id > " . intval($offset);
        return self::all($args);
    }
    
    
    
    
    
      
}
?>
