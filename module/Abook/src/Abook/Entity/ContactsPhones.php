<?php

namespace Abook\Entity;

class ContactsPhones {
    
    private $id;
    
    private $contactId;
    
    private $phoneNumber;
    
    private $active;
    
    private $deleted;
    
    public function getId() {
        return $this->id;
    }

    public function getContactId() {
        return $this->contactId;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function getActive() {
        return $this->active;
    }

    public function getDeleted() {
        return $this->deleted;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }


    
}