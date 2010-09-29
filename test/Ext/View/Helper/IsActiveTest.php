<?php

class Ext_View_Helper_IsActiveTest extends PHPUnit_Framework_TestCase
{
    private $_helper;

    public function setUp()
    {
        $request = new Zend_Controller_Request_HttpTestCase();
        $request->setParam('module', 'module')
            ->setParam('controller', 'controller')
            ->setParam('action', 'action');
        Zend_Controller_Front::getInstance()->setRequest($request);
        $this->_helper = new Ext_View_Helper_IsActive();
    }

    public function testDirect()
    {
        $this->assertTrue($this->_helper->isActive('module'));
        $this->assertFalse($this->_helper->isActive('another_module'));

        $this->assertTrue($this->_helper->isActive('another_module', 'module:controller'));
        $this->assertFalse($this->_helper->isActive('another_module', 'module:another_controller'));

        $this->assertTrue($this->_helper->isActive('module:controller:action'));
        $this->assertFalse($this->_helper->isActive('module:controller:another_action'));
    }
}