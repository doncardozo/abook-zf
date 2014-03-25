<?php

namespace Abook\Entity;

class ContactType {
    
    private $id;
    
    private $name;
    
    private $active;
    
    private $delete;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getActive() {
        return $this->active;
    }

    public function getDelete() {
        return $this->delete;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setDelete($delete) {
        $this->delete = $delete;
    }
    
}