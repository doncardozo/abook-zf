<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ContactsEmails extends ManagerAbstract {
    
    public function getContactsEmailsByContact($id){
        
                $select = <<<SQL
            select 
                id, 
                first_name, 
                last_name,
                address
            from contacts            
SQL;
        
        $stmt = $this->getAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            return $resultSet->toArray();
        }
    }
    
}
