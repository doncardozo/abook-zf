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
            "name" => "id",
            "options" => array(
                "type" => "hidden"
            )
        ));

        $this->add(array(
            "name" => "firstName",
            "options" => array(
                "label" => "First Name"
            ),
            "attributes" => array(
                "class" => "form-control",
                "required" => "required"
            )
        ));

        $this->add(array(
            "name" => "lastName",
            "options" => array(
                "label" => "Last Name"
            ),
            "attributes" => array(
                "class" => "form-control",
                "required" => "required"
            )
        ));

        $this->add(array(
            "name" => "address",
            "options" => array(
                "label" => "Address"
            ),
            "attributes" => array(
                "class" => "form-control",
                "required" => "required"
            )
        ));

        $this->add(array(
            "name" => "active",
            "type" => "checkbox",
            "options" => array(
                "label" => "Active"
            ),
            "attributes" => array(
                "class" => "form-control",
                "required" => "required"
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
                'label' => 'Type'
            ),
        ));

        $this->add(array(
            "type" => "Zend\Form\Element\Collection",
            "name" => "emails",
            "options" => array(
                "label" => "Emails",
                "count" => 1,
                "should_create_template" => true,
                "allow_add" => true,
                "allow_remove" => true,
                "create_new_objects" => true,
                "target_element" => array(
                    "type" => "Abook\Form\ContactsEmailsFieldset",
                ),
            ),
        ));

        $this->add(array(
            "type" => "Zend\Form\Element\Collection",
            "name" => "phones",
            "options" => array(
                "label" => "Phones",
                "count" => 1,
                "should_create_template" => true,
                "allow_add" => true,
                "target_element" => array(
                    "type" => "Abook\Form\ContactsPhonesFieldset",
                ),
            ),
        ));
    }

    public function getInputFilterSpecification() {

        return array(
            "firstName" => array(
                "required" => true,
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
                )),
            "lastName" => array(
                "required" => true,
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
                )),
            "address" => array(
                "required" => true,
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
                ),
        ));
    }

}
