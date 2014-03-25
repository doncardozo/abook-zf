<?php

namespace Abook\Model;

use Abook\Toolbox\Db\AbstractAdapterManager;
use Abook\Toolbox\Db\ResultSetManager;
use Zend\Db\TableGateway\TableGateway;

class ContactsModel extends AbstractAdapterManager {

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

        $rs = new ResultSetManager();        
        return $rs->getResultSet($result);
    }

    public function fetchById($id) {

        $select = <<<SQL
            select 
                id, 
                first_name firstName, 
                last_name lastName,
                address,
                contact_type_id contactType,
                active
            from contacts            
            where id = {$id}
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();
        
        $rs = new ResultSetManager();

        $row = $rs->getResultSet($result)->current();
        
        if (!$row) {
            throw new \Exception("Could not find row {$id}");
        }

        return $row;        
    }
    
    public function getContactType(){
        
        $select = <<<SQL
            select 
                id, 
                name
            from contact_type
            where 
                active = 1 and deleted = 0;
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();
        
        $rs = new ResultSetManager();

        $result = $rs->getResultSet($result);
        
        return $result;
    }

    public function create(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress(),
            'contact_type_id' => $contact->getContactType(),
            'active' => $contact->getActive()
        );

        $contactsTable = new TableGateway("contacts", $this->getDbAdapter());

        $contactsTable->insert($data);
        
        return $contactsTable->getLastInsertValue();
    }

    public function update(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress(),
            'contact_type_id' => $contact->getContactType(),
            'active' => $contact->getActive()
        );

        if ($this->fetchById($contact->getId())) {
            $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
            $contactsTable->update($data, array("id" => $contact->getId()));
        }
    }

}
