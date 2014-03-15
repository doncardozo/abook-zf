<?php

namespace Abook\Form;

use Abook\Entity\Contacts;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactsFieldset extends Fieldset implements InputFilterProviderInterface {
    
    public function __construct($name = null, $options = array()) {
        
        parent::__construct("contacts");
        
        $this->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Contacts());
        
        $this->add(array(
            "name" => "firstName",
            "options" => array(
                "label" => "First Name"
            ),
            "attributes" => array(
                "required" => "required",
                "class" => "form-control"
            )
        ));
        
        $this->add(array(
            "name" => "lastName",
            "options" => array(
                "label" => "Last Name"
            ),
            "attributes" => array(
                "required" => "required",
                "class" => "form-control"
            )
        ));
        
        $this->add(array(
            "name" => "address",
            "options" => array(
                "label" => "Address"
            ),
            "attributes" => array(
                "required" => "required",
                "class" => "form-control"
            )
        ));
        
    }


    public function getInputFilterSpecification() {
        return array(
            "name" => array(
                "required" => true
            )
        );
    }

}

