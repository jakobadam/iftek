<?php

include('form.php');

class PostForm extends Form{
	
	# FIXME: WFT? How to put object stuff in that array here???
	var $fields = array(); 
	var $title;
	var $body;
	var $is_published;
	
	function __construct($data=array()){
		
		$this->title = new Field('title', array(new Required()));
		$this->is_published = new Field('is_published');
		$this->body = new Field('body', array(new Required()));

		// FIXME: move theese to be instance variables!
		array_push($this->fields, $this->title, $this->is_published, $this->body);
		
		parent::__construct($data);
	}
	
}
?>