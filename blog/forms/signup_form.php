<?php

include('form.php');

class SignupForm extends Form{
	
	var $fields = array(); 
	
	function __construct($data=array()){
		
		$this->email = new Field('email', array(new Required()));
        $this->name = new Field('name', array(new Required()));
		$this->password = new Field('password', array(new Required()));
        $this->password2 = new Field('password2', array(new Required()));

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