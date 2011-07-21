<?php

include('form.php');

class LoginForm extends Form{
	
	# FIXME: WFT? How to put object stuff in that array here???
	var $fields = array(); 
	var $email;
	var $password;
	
	function __construct($data=array()){
		
		// FIXME: variable number of arguments? The validators...
		$this->email = new Field('email', array(new Required()));
		$this->password = new Field('password', array(new Required()));

		// FIXME: move theese to be instance variables!
		array_push($this->fields, $this->email, $this->password);
		
		parent::__construct($data);
	}
	
}
?>