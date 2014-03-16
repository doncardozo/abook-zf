<?php

namespace Abook\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class CreateContacts extends Form {
    
    public function __construct() {
        
        parent::__construct("create_contact");
        
        $this->setAttributes(array(
                "method" => "post", 
                "autocomplete" => "off")
             )
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter());
     
        $this->add(array(
            "type" => "Abook\Form\ContactsFieldset",
            "options" => array(
                'use_as_base_fieldset' => true
            )
        ));
        
        $this->add(array(
            "type" => "Zend\Form\Element\Csrf",
            "name" => "csrf"
        ));

        $this->add(array(
            'name' => 'submit',            
            'attributes' => array(
                'type' => 'submit',
                'id' => 'submit',
                'value' => 'Save',
                'class' => 'btn btn-primary'
            )
        ));
    }
    
}