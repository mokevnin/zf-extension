<?php

class Ext_File_LocalsTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Ext_File_Webdav
     */
    public $fs;

    public function setUp()
    {
        $this->fs = new Ext_File_Local();
    }

    public function testIsDir()
    {
        $this->assertTrue($this->fs->isDir(dirname(__FILE__)));
    }
}