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

    public function setEmails(array $emails) {
        $this->emails = array();
        foreach($emails as $email){                                                
            $id = is_array($email) ? $email["id"] : $email->getId();
            $email = is_array($email) ? $email["email"] : $email->getEmail();                        
            $entity = new ContactsEmails();
            $entity->setId($id);
            $entity->setEmail($email);            
            $this->emails[] = $entity;
        }        
    }

    public function setPhones($phones) {
        $this->phones = array();
        foreach($phones as $phone){                        
            $id = is_array($phone) ? $phone["id"] : $phone->getId();
            $phone = is_array($phone) ? $phone["phoneNumber"] : $phone->getPhoneNumber();                        
            $entity = new ContactsPhones();
            $entity->setId($id);            
            $entity->setPhoneNumber($phone);            
            $this->phones[] = $entity;
        }  
    }
                
    public function hydrate($data){
        $hydrator = new ClassMethods();
        $hydrator->hydrate((array)$data, $this);       
    }

}