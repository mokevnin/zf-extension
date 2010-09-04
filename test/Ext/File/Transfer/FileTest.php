<?php

class Ext_File_Transfer_FileTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Ext_File_Transfer_File
     */
    public $transferFile;
    public $config;
    public $formName = 'test_form_name';

    public function setUp()
    {
        $this->config = array(
            'tmp_name' => '/tmp/3245',
            'error' => 0,
            'name' => 'source_name'
        );
        $this->transferFile = new Ext_File_Transfer_File($this->formName, $this->config);
    }

    public function testGetFsAccess()
    {
        $this->assertEquals($this->formName, $this->transferFile->getFileId());
    }
}