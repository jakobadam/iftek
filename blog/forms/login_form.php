<?php

include('form.php');

class LoginForm extends Form{
	
	var $fields = array(); 
	
	function __construct($data=array()){
		
		$this->email = new Field('email', array(new Required()));
		$this->password = new Field('password', array(new Required()));

		array_push($this->fields, $this->email, $this->password);
		
		parent::__construct($data);
	}
}
?>