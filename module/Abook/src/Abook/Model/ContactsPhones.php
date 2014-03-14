<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ContactsPhones extends ManagerAbstract {
    
    public function getContactsPhonesByContact($id){
        
                $select = <<<SQL
            select 
                id, 
                contact_id, 
                phone_number
            from contacts            
SQL;
        
        $stmt = $this->getAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            return $resultSet;
        }
    }
    
}
