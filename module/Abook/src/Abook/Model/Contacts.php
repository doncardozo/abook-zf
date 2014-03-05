<?php

namespace Abook\Model;

use Abook\Model\ManagerAbstract;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class Contacts extends ManagerAbstract {

    public function getContacts() {

        $stmt = $this->adapter->createStatement("select id, first_name, last_name from contacts");
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
