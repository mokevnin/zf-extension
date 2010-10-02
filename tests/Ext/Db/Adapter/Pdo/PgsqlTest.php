<?php

class Ext_Db_Adapter_Pdo_PgsqlTest extends PHPUnit_Framework_TestCase
{
    private $_adapter;

    public function setUp()
    {
        $optiosn = array(
            'dbname' => 'dbname',
            'password' => 'password',
            'username' => 'username'
        );
        $this->_adapter = new Ext_Db_Adapter_Pdo_Pgsql($optiosn);
    }

    public function testNestedTransactions()
    {
        $adapter = $this->_adapter;
        $this->assertFalse($adapter->isTransactionStarted());
        $this->markTestIncomplete();
    }
}