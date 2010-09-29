<?php

class Ext_Controller_Action_Helper_ThemeTest extends PHPUnit_Framework_TestCase
{
    private $_helper;

    public function setUp()
    {
        Zend_Controller_Front::getInstance()->addModuleDirectory(dirname(__FILE__));
        $this->_helper = new Ext_Controller_Action_Helper_Theme();
    }

    public function  testPostDispath()
    {
        $this->markTestIncomplete();
    }
}