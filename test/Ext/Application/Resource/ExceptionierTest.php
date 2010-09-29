<?php

class Ext_Application_Resource_ExceptionizerTest extends PHPUnit_Framework_TestCase
{
    private $_resource;

    public function  setUp()
    {
        $this->_resource = new Ext_Application_Resource_Exceptionizer();
    }

    public function testInit()
    {
        $this->assertTrue($this->_resource->init() instanceof PHP_Exceptionizer);
    }
}