<?php

namespace Abook\Service;

class EmailsPhonesControl {
    
    private $curr;
    private $new;
    
    public function setData(array $current, array $new){
        $this->set("curr", $current);        
        $this->set("new", $new);        
    }
    
    public function getArray(){
        return array(
            "insert" => $this->getToInsert(),
            "update" => $this->getToUpdate(),
            "delete" => $this->getToDelete()
        );            
    }
    
    private function set($key, $data){
        $this->{$key} = array();
        foreach($data as $obj){            
            $this->{$key}[$obj->getId()] = $obj;      
        }        
    }
    
    public function getToInsert(){
        return array_diff_key($this->curr, $this->new);
    }
    
    public function getToUpdate(){
        return array_intersect_key($this->new, $this->curr);
    }
    
    public function getToDelete(){
        return array_diff_key($this->new, $this->curr);
    }
    
    
    
}