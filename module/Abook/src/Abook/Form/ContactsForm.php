<?php

namespace Abook\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactsForm extends Form {

    protected $servLoc;
            
    public function __construct($name = null, $serviceLocator = null, $options = array()) {

        parent::__construct($name, $options);

        $this->servLoc = $serviceLocator;

        $inputFilter = new InputFilter();
        
        $this->setAttributes(array(
                    "method" => "post",
                    "autocomplete" => "off")
                )
                ->setHydrator(new ClassMethodsHydrator(false))
                ->setInputFilter($inputFilter);

        $this->add(array(
            "type" => "Abook\Form\ContactsFieldset",
            "options" => array(
                'use_as_base_fieldset' => true
            )
        ));
        
        /* ContactType */
        $contactType = $this->get('contacts')
                            ->get('contactType')
                            ->setOptions(array("value_options" => $this->getContactTypeValues()));
        
        $this->add($contactType); 
        
        $inputFilter->add(array(
            "name" => "contactType",
            "required" => false
        ));
        /* ContactType */
        
        $this->add(array(
            "type" => "Zend\Form\Element\Csrf",
            "name" => "csrf"
        ));
    }
    
    public function getContactTypeValues(){
        
        if(!is_null($this->servLoc)){
            $contactTypeValues = $this->servLoc
                                      ->get("Abook\Model\ContactsModel")
                                      ->getContactType();
        }
        
        if(sizeof($contactTypeValues) == 0){
            return array();
        }
        else {
            
            foreach($contactTypeValues as $item){
                $data[$item->id] = $item->name;
            }
            
            return $data;
        }
        
    }

}
