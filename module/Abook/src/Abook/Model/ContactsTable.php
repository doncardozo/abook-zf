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
        });
        
        return $rowset;
    }
    
    function fetchById($id){
        
    }
    
    function create(){
        
    }
    
    

}
