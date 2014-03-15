<?php

namespace Abook\Entity;

class ContactsEmails {
    
    private $id;
    
    private $contactId;
    
    private $email;
    
    private $active;
    
    private $deleted;
    
    public function getId() {
        return $this->id;
    }

    public function getContactId() {
        return $this->contactId;
    }

    public function getEmail() {
        return $this->email;
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

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }
    
}