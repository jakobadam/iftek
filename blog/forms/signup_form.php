<?php

include('form.php');

class SignupForm extends Form{
	
	# FIXME: WFT? How to put object stuff in that array here???
	var $fields = array(); 
	var $email;
	var $password;
    var $password2;
	
	function __construct($data=array()){
		
		// FIXME: variable number of arguments? The validators...
		$this->email = new Field('email', array(new Required()));
        $this->name = new Field('name', array(new Required()));
		$this->password = new Field('password', array(new Required()));
        $this->password2 = new Field('password2', array(new Required()));

		// FIXME: move theese to be instance variables!
		array_push($this->fields, $this->name, $this->email, $this->password, $this->password2);
		
		parent::__construct($data);
	}
    
    function validate(){
        if($this->password->data != $this->password2->data){
            throw new Exception("Kodeord er ikke ens!");
        }
    }
	
}
?>