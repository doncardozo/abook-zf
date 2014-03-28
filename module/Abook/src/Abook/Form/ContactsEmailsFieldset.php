<?php

namespace Abook\Form;

use Abook\Entity\ContactsEmails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactsEmailsFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct($name = null, $options = array()) {

        parent::__construct("contacts_emails");

        $this->setHydrator(new ClassMethodsHydrator(false))
                ->setObject(new ContactsEmails());

        $this->add(array(
            "name" => "id",
            "type" => "hidden",
            "options" => array()
        ));

        $this->add(array(
            "name" => "email",
            "options" => array(
                "label" => ""
            ),
            "attributes" => array(
                "class" => "form-control",
                "style" => "margin-bottom: 10px;"
            )
        ));        
    }

    public function getInputFilterSpecification() {

        return array(
            "email" => array(
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
