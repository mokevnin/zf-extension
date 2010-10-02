<?php

class Ext_Db_Table_Mock extends Ext_Db_Table_Abstract
{
    protected $_name = 'mock';
    
    public function info($key = null)
    {
        return array(
            Zend_Db_Table_Abstract::NAME => $this->_name,
            Zend_Db_Table_Abstract::SCHEMA => null
        );
    }
}