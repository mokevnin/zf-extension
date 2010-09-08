<?php

class Ext_File_Transfer_Adapter_Local extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_adapter = new Ext_File_Transfer_Adapter_Local();
        $this->_transfer = new Ext_File_Transfer($this->_adapter);
    }

    public function testUsage()
    {
        
    }
}