<?php

class Ext_View_Helper_InnerTest extends PHPUnit_Framework_TestCase
{
    private $_helper;

    public function setUp()
    {
        $this->_helper = new Ext_View_Helper_Inner();
    }

    public function testDirect()
    {
        $this->assertFalse($this->_helper->check());
        $this->assertTrue($this->_helper->check());
        $this->_helper->reset();
        $this->assertFalse($this->_helper->check());
    }
}