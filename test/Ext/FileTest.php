<?php

class Ext_FileTest extends PHPUnit_Framework_TestCase
{
    public $path = '/test/path/to/file.so';
    public $file;

    public function  setUp()
    {
        $this->file = new Ext_File($this->path);
    }
    
    public function testGetFilePath()
    {
        $this->assertEquals($this->path, $this->file->getFilePath());
    }
}