<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ContactsTable extends ManagerAbstract {

    public function fetchAll() {

        $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
        $rowset = $contactsTable->select(function(\Zend\Db\Sql\Select $select) {
            $select->columns(array("id", "first_name", "last_name", "address"));
            $select->where(array("active" => 1, "deleted" => 0));
            $select->order(array("id" => "desc"));
        });

        return $rowset;
    }

    public function fetchById($id) {

        $select = <<<SQL
            select 
                id, 
                first_name firstName, 
                last_name lastName,
                address
            from contacts            
            where id = {$id}
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            $row = $resultSet->current();
            if (!$row) {
                throw new \Exception("Could not find row {$id}");
            }

            return $row;
        }
        else {
            throw new \Exception();
        }
    }

    public function create(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress()
        );

        $contactsTable = new TableGateway("contacts", $this->getDbAdapter());

        $contactsTable->insert($data);
    }

}
