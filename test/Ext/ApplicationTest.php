<?php

class Ext_ApplicationTest extends PHPUnit_Framework_TestCase
{
    private $_application;

    public function setUp()
    {
        $this->_application = new Ext_Application('test');
    }

    public function testApplicationPath()
    {
        $path = '/application/path';
        $this->_application->setApplicationPath($path);
        $this->assertEquals($path, $this->_application->getApplicationPath());
    }
}