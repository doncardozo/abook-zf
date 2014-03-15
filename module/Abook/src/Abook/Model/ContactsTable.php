<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\TableGateway\TableGateway;

class ContactsTable extends ManagerAbstract {
    
    public function fetchAll() {

        $contactsTable = new TableGateway("contacts", $this->getDbAdapter());   
        $rowset = $contactsTable->select(function(\Zend\Db\Sql\Select $select){
            $select->columns(array(
                "id", "first_name", "last_name", "address"
            ));
            
            $select->where(array("active"=>1,"deleted"=>0));
            $select->order(array("id"=>"desc"));            
        });
        
        return $rowset;
    }
    
    function fetchById($id){
        
    }
    
    function create(\Abook\Model\Contacts $contact){
        
        return;
        $data = array(
            'first_name' => $contact->firstName,
            'last_name' => $contact->lastName,
            'address' => $contact->address
        );
        
        $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
        
        $contactsTable->insert($data);
        
    }
    
    

}
