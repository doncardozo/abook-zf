<?php

namespace Abook\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;

abstract class ManagerAbstract implements AdapterAwareInterface {

    /**
     * @var Zend\Db\Adapter\Adapter
     */
    protected $adapter;
    
    public function setDbAdapter(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function getDbAdapter(){
        return $this->adapter;
    }
    
    public function getConnection(){
        return $this->adapter->getDriver()->getConnection();
    }

}
