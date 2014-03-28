<?php

namespace Abook\Form;

use Abook\Entity\ContactsPhones;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactsPhonesFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct($name = null, $options = array()) {

        parent::__construct("contacts_phones");

        $this->setHydrator(new ClassMethodsHydrator(false))
                ->setObject(new ContactsPhones());

        $this->add(array(
            "name" => "id",
            "type" => "hidden",
            "options" => array()
        ));

        $this->add(array(
            "name" => "phoneNumber",
            "options" => array(
                "label" => ""
            ),
            "attributes" => array(
                "class" => "form-control"
            )
        ));        
    }

    public function getInputFilterSpecification() {

        return array(
            "phoneNumber" => array(
                "required" => false,
                "filters" => array(
                    array("name" => "StripTags"),
                    array("name" => "StringTrim"),
                ),
                "validators" => array(
                    array(
                        "name" => "StringLength",
                        "options" => array(
                            "encoding" => "UTF-8",
                            "min" => 4,
                            "max" => 100,
                        ),
                    ),
                )));
    }

}
