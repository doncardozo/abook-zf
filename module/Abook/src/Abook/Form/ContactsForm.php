<?php

namespace Abook\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactsForm extends Form {

    public function __construct($name = null, $contactModel = null, $options = array()) {

        parent::__construct($name, $options);

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
            'name' => 'contactType',
            'type' => 'select',
            'attributes' => array(
                'id' => 'contactType',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Type',   
                'value_options' => $contactModel->getArrayContactType(),
                
            ),
        ));        

        $this->add(array(
            "type" => "Zend\Form\Element\Csrf",
            "name" => "csrf"
        ));
    }

}
