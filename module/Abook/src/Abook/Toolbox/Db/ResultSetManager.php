<?php

namespace Abook\Toolbox\Db;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ResultSetManager {
    
    public function getResultSet(ResultInterface $result){
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            return $resultSet;
        }
       
    }    
    
}