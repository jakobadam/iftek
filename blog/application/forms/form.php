<?php

/**
 * Validator for felt.
 * 
 * Validerer at feltet har en værdi.
 */
class Required{
	
	var $message;
	
	function __construct($message='Dette felt skal udfyldes!'){
		$this->message = $message;	
   	}
	
	function validate($field){
		if(!$field->data || $field->data == ''){
			throw new Exception($this->message);
		}
	}
}

/**
 * Et form felt.
 */
class Field{
	
	var $validators;
	var $data;
	var $name;
	var $errors = array();
		
	function __construct($name=Null, $validators=array()){
		$this->name = $name;
		$this->validators = $validators;
	}
	
	function process($data){
		# FIXME: Error when not present?
		$this->data = $data[$this->name];					
	}
	
	function validate(){
		foreach($this->validators as $validator){
			try{
				$validator->validate($this);			
			}	
			catch(exception $e){
				array_push($this->errors, $e->getMessage());
			}
		}
		return count($this->errors) == 0;
	}
	
	function populate_obj($obj){
		$name = $this->name;
		$obj->$name = $this->data;
	}
}

class Form{
	
	function __construct($data=array()){
		
		foreach($this->fields as $field){
			$field->process($data);
		}
	}
	
	function populate_obj($obj){	
		foreach ($this->fields as $name => $field) {
			$field->populate_obj($obj);
		}
	}
	
	function validate_on_submit(){
		$success = true;
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			foreach($this->fields as $field){
				if(!$field->validate()){	
					$success = false;
				}
			}
			return $success;
		}
		return false;
	}
	
	
}
?>