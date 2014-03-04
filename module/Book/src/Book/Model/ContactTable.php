<?php

namespace Book\Model;

use Zend\Db\TableGateway\TableGateway;

class ContactTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getContact($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id_contact' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveContact(Contact $contact) {
        $data = array(
            'first_name' => $contact->fname,
            'last_name' => $contact->lname,
            'address' => $contact->address,
        );

        $id = (int) $contact->idc;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getContact($id)) {
                $this->tableGateway->update($data, array('id_contact' => $id));
            } else {
                throw new \Exception('Contact id does not exist');
            }
        }
    }

    public function deleteContact($id) {
        $this->tableGateway->delete(array('id_contact' => (int) $id));
    }

}
