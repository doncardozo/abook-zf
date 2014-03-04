<?php

namespace Book\Form;

use Zend\Form\Form;

class ContactForm extends Form {

    
    public function __construct($name = null) {
        
        parent::__construct($name);
        
        $this->add(array(
            'name' => 'first_name',
            'type' => 'text',
            /*'options' => array(
                'label' => 'Fist Name'
            ),*/
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'First Name'
            )
        ));
        
        $this->add(array(
            'name' => 'last_name',
            'type' => 'text',
            /*'options' => array(
                'label' => 'Last Name'
            ),*/
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Last Name'
            )
        ));
        
        $this->add(array(
            'name' => 'address',
            'type' => 'text',
            /*'options' => array(
                'label' => 'Address'
            ),*/
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Address'
            )
        ));
        
        $this->add(array(
            'name' => 'txt_email',
            'type' => 'text',
            /*'options' => array(
                'label' => 'E-mail'
            ),*/
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'E-mail'
            )
        ));
        
        $this->add(array(
            'name' => 'txt_phone',
            'type' => 'text',
            /*'options' => array(
                'label' => 'Phone'            
            ),*/
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Phone number'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
            ),
        ));
    }

}
