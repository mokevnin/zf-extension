<?php

class Ext_File_Transfer_HttpPostTest extends PHPUnit_Framework_TestCase
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
    
    public $destination = '/example/path';

    public function setUp()
    {
        $_FILES = array(
            'multi_file' => array(
                'name' => array('first_file_name', 'second_file_name'),
                'type' => array('first_file_type', 'second_file_type'),
                'tmp_name' => array('first_file_tmp_name', 'second_file_tmp_name'),
                'error' => array('first_file_error', 'second_file_error'),
                'size' => array('first_file_size', 'second_file_size'),
            ),
            'file' => array(
                'name' => 'name',
                'type' => 'type',
                'tmp_name' => 'tmp_name',
                'error' => 'error',
                'size' => 'size',
            )
        );
        
        $this->fs = new Ext_File_Local();
        $this->transfer = new Ext_File_Transfer_HttpPost($this->fs);
    }

    public function testGetFsAccess()
    {
        $this->assertEquals($this->fs, $this->transfer->getFsAccess());
    }

    public function testGetFiles()
    {
        $files = $this->transfer->getFiles();
        $this->assertEquals(3, sizeof($files));

        $files = $this->transfer->getFiles('multi_file');
        $this->assertEquals(2, sizeof($files));
        //TODO
    }

    public function testSetDestination()
    {
        $this->transfer->setDestination($this->destination);
        //TODO
    }
}