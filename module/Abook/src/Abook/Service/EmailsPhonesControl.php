<?php

namespace Abook\Service;

class EmailsPhonesControl {

    private $curr;
    private $new;
    private $insert;
    private $update;
    private $delete;

    public function setData(array $current, array $new) {
        $this->set("curr", $current);
        $this->set("new", $new);
        $this->setToInsert();
        $this->setToUpdate();
        $this->setToDelete();
    }

    public function getArray() {
        return array(
            "insert" => $this->getToInsert(),
            "update" => $this->getToUpdate(),
            "delete" => $this->getToDelete()
        );
    }

    private function set($key, $data) {
        $this->{$key} = array();
        foreach ($data as $obj) {
            $this->{$key}[$obj->getId()] = $obj;
        }
    }

    private function setToInsert() {        
        $this->insert = array_diff_key($this->new, $this->curr);
    }

    private function setToUpdate() {
        $result = array_intersect_key($this->curr, $this->new);
        $this->update = array();
        if (sizeof($result) > 0) {
            foreach ($result as $res) {
                foreach ($this->new as $new) {
                    if ($res->getId() == $new->getId()) {
                        $this->update[$res->getId()] = $new;
                    }
                }
            }
        }
    }

    private function setToDelete() {
        $this->delete = array_diff_key($this->curr, $this->new);
    }

    public function getToInsert() {
        return $this->insert;
    }

    public function getToUpdate() {
        return $this->update;
    }

    public function getToDelete() {
        return $this->delete;
    }

}
