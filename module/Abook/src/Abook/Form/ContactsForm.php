<?php

namespace Abook\Form;

use Zend\Form\Form;


class ContactsForm extends Form {
    
    public function __construct($name = null, $options = array()) {
        parent::__construct($name, $options);
        
        $this->add(array(
            'name' => 'first_name',
            'type' => 'text',
            'options' => array(
                'label' => 'First Name'
            ),
            'attributes' => array(
                'class' => 'form-control',
                #'placeholder' => 'First Name'
            )
        ));
        
        $this->add(array(
            'name' => 'last_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Last Name'
            ),
            'attributes' => array(
                'class' => 'form-control',
                #'placeholder' => 'Last Name'
            )
        ));
        
        $this->add(array(
            'name' => 'address',
            'type' => 'text',
            'options' => array(
                'label' => 'Address'
            ),
            'attributes' => array(
                'class' => 'form-control',
                #'placeholder' => 'Address'
            )
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