<?php

require_once("model.php");
require_once("base_controller.php");

class Post extends Model{
    
    var $body;
    var $title;
    var $is_published;
    var $user_id;
    
    function url(){
        return url_root() . 'post.php?id=' . $this->id;
    }
    
}
?>
