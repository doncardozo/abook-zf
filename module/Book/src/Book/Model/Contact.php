<?php

namespace Book\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Contact implements InputFilterAwareInterface {

    public $idc;
    public $fname;
    public $lname;
    public $address;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->idc = (!empty($data['id_contact'])) ? $data['id_contact'] : null;
        $this->fname = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lname = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->address = (!empty($data['address'])) ? $data['address'] : null;
    }

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(
                    array( 'name' => 'StripTags' ),
                    array( 'name' => 'StringTrim' ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 80
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(
                    array( 'name' => 'StripTags' ),
                    array( 'name' => 'StringTrim' ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 80
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'address',
                'required' => true,
                'filters' => array(
                    array( 'name' => 'StripTags' ),
                    array( 'name' => 'StringTrim' ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 80
                        )
                    )
                )
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}
