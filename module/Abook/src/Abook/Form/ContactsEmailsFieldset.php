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
            "attributes" => array(
                "data-delete" => "off"
            )
        ));

        $this->add(array(
            "name" => "email",
            "options" => array(
                "label" => "",
            ),
            "attributes" => array(
                "class" => "form-control",                
                "data-delete" => "off",
                "placeholder" => "New Email"
            )
        ));

        $this->add(array(
            "name" => "rem",
            "type" => "button",
            "options" => array(
                "label" => "Remove"
            ),
            "attributes" => array(
                "class" => "btn btn-primary",                
                "id" => "rem",
                "data-delete" => "off"
            )
        ));
    }

    public function getInputFilterSpecification() {

        return array(
            "email" => array(
                "required" => true,
                "filters" => array(
                    array("name" => "StripTags"),
                    array("name" => "StringTrim"),
                ),
                "validators" => array(
                    new \Zend\Validator\EmailAddress()
                )
        ));
    }

}
