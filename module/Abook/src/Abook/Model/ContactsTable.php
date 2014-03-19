<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator;

class ContactsTable extends ManagerAbstract {

    public function fetchAll() {

        $select = <<<SQL
            select 
                id, 
                first_name firstName, 
                last_name lastName,
                address
            from contacts            
            where 
                deleted = 0
            order by 1 desc;
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            return $resultSet;
        }
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

            $contacts = new \Abook\Entity\Contacts();
            $hydrator = new Hydrator\ClassMethods();
            $hydrator->hydrate((array) $row, $contacts);

            return $contacts;
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
        $a = $contactsTable->getLastInsertValue();
    }

    public function update(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress()
        );

        if ($this->fetchById($contact->getId())) {
            $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
            $contactsTable->update($data, array("id" => $contact->getId()));
        }
    }

}
