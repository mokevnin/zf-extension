<?php

class Ext_Db_Table_SelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ext_Db_Table_Select
     */
    private $_select;


    public function setUp()
    {
        $setup = new Ext_Db_TestSetup();
        $util = new Zend_Db_TestUtil_Pdo_Pgsql();
        $db = Zend_Db::factory('Pdo_Pgsql', $util->getParams());
        $table = new Ext_Db_Table_Mock($db);
        $this->_select = new Ext_Db_Table_Select($table);
        
    }

    public function testWhere()
    {
        $this->_select->where('outer_cond')
            ->openAnd()
            ->where('first_inner_cond')
            ->OrWhere('second_inner_cond')
            ->closeBlock();
        $expected = 'SELECT "mock".* FROM "mock" WHERE  (outer_cond) AND ( (first_inner_cond) OR (second_inner_cond) )';
        $this->assertEquals($expected, $this->_select->__toString());
    }
}