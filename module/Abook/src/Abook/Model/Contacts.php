<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class Contacts extends ManagerAbstract {
    
//    public function getContacts(){
//        
//        $sql = new Sql($this->adapter);
//        $select = $sql->select();
//        $select->columns(array("id","first_name", "last_name"));
//        $select->from("contacts");
//        
//        $stmt  = $sql->prepareStatementForSqlObject($select);
//        $result = $stmt->execute();
//        
//        if ($result instanceof ResultInterface && $result->isQueryResult()) {
//            $resultSet = new ResultSet;
//            $resultSet->initialize($result);
//            return $resultSet->toArray();
//        }
//    }

    public function getContacts() {

        $select = <<<SQL
            select 
                id, first_name, last_name 
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

        return array();
    }

}
