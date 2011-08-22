<?php

require_once("model.php");
require_once("dao/user_dao.php");
require_once("base_controller.php");

class Post extends Model{
    
    var $body;
    var $title;
    var $is_published;
    var $user_id;
    
    var $_user;
    
    function url(){
        return url_root() . 'post.php?id=' . $this->id;
    }
    
    function edit_url(){
        return url_root() . 'edit_post.php?id=' . $this->id;
    }
    
    function excerpt(){
        // FIXME: mere logik her dette ødelægger html'en!
        return substr($this->body, 0, 100);
    }
    
    function user(){
        if($this->_user == null){
            $this->_user = User_DAO::get($this->user_id);
        }
        return $this->_user;
    }
    
}
?>
