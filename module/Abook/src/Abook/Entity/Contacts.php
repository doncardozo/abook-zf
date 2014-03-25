<?php

namespace Abook\Entity;

use Zend\Stdlib\Hydrator\ClassMethods;

class Contacts {
    
    private $id;
    
    private $firstName;
    
    private $lastName;
    
    private $address;
    
    private $contactType;
    
    private $active;
    
    private $emails;
    
    private $phones;
    
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getContactType() {
        return $this->contactType;
    }

    public function getActive() {
        return $this->active;
    }

    public function getEmails() {
        return $this->emails;
    }

    public function getPhones() {
        return $this->phones;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setContactType($contactType) {
        $this->contactType = $contactType;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setEmails($emails) {
        $this->emails = $emails;
    }

    public function setPhones($phones) {
        $this->phones = $phones;
    }
                
    public function hydrate($data){
        $hydrator = new ClassMethods();
        $hydrator->hydrate((array)$data, $this);       
    }

}