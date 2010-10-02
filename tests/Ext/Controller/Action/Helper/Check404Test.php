<?php

class Ext_Controller_Action_Helper_Check404Test extends PHPUnit_Framework_TestCase
{
    private $_helper;

    public function setUp()
    {
        $this->_helper = new Ext_Controller_Action_Helper_Check404();
    }

    public function testEmptyValue()
    {
        try {
            $this->_helper->direct('');
            $this->fail('Expected exception');
        } catch (Zend_Controller_Action_Exception $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testValue()
    {
        $this->assertTrue($this->_helper->direct('value'));
    }
}