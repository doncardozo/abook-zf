<?php

namespace Abook\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;

class ContactsTable extends AbstractTableGateway {
    
    protected $table;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
}