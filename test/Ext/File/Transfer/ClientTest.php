<?php

class Ext_File_Transfer_ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Ext_File_Transfer_Client
     */
    public $transfer;
    
    /**
     *
     * @var Ext_File_Local
     */
    public $fs;

    public function setUp()
    {
        $this->fs = new Ext_File_Local();
        $this->transfer = new Ext_File_Transfer_Client($this->fs);
    }

    public function testGetFsAccess()
    {
        $this->assertEquals($this->fs, $this->transfer->getFsAccess());
    }
}