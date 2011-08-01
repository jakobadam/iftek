<?php
/**
 * Validator for felt.
 *
 * Validerer at feltet har en værdi.
 */
class Required {

    var $message;

    function __construct($message='Dette felt skal udfyldes!') {
        $this -> message = $message;
    }

    function validate($field) {
        if(!$field -> data || $field -> data == '') {
            throw new Exception($this -> message);
        }
    }

}

/**
 * Et form felt.
 */
class Field {

    var $validators;
    var $data;
    var $name;
    var $errors = array();

    function __construct($name=Null, $validators= array()) {
        $this -> name = $name;
        $this -> validators = $validators;
    }

    function validate() {
        foreach($this->validators as $validator) {
            try {
                $validator -> validate($this);
            } catch(exception $e) {
                array_push($this -> errors, $e -> getMessage());
            }
        }
        return count($this -> errors) == 0;
    }
    
    function process($values) {
        if(array_key_exists($this -> name, $values)) {
            $value = $values[$this->name];
            if($value){
                $this->data = $value;            
            }
        } 
 
    }
    
    function errors_as_html() {
        if(count($this -> errors) > 0) {
            $html = array();
            array_push($html, '<ul class="error">');
            foreach($this->errors as $err) {
                array_push($html, "<li>$err</li>");
            }
            array_push($html, '</ul>');
            return  join('', $html);
        }
        return '';
    }

    function populate_obj($obj) {
        $name = $this -> name;
        $obj -> $name = $this -> data;
    }

}

class Checkbox extends Field {
 
    function process($values) {
        // A html form checkbox doen't submit a value at all if unchecked
        if(array_key_exists($this -> name, $values)) {
            $this -> data = 1;
        } else {
            $this -> data = 0;
        }
    }

}

class Form {

    /**
     * Konstruer form udfra den givne ordbog. 
     * 
     * @param $data POST.
     * @param $instance objekt med default værdier.
     */
    function __construct($values=array(), $instance=null) {
        if($instance) {
            foreach(get_object_vars($instance) as $key => $value) {
                if(property_exists($this, $key)) {
                    $this -> $key -> data = $value;
                }
            }
        }
    
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            foreach($this->fields as $field) {   
                $field->process($values);        
            }
        }
    }

    function populate_obj($obj) {
        foreach($this->fields as $name => $field) {
            $field -> populate_obj($obj);
        }
    }

    function validate_on_submit() {
        $success = true;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($this->fields as $field) {
                if(!$field -> validate()) {
                    $success = false;
                }
            }
            return $success;
        }
        return false;
    }

}?>