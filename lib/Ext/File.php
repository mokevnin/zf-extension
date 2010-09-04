<?php

class Ext_File
{
    protected $_filePath;

    public function  __construct($filePath)
    {
        $this->_filePath = $filePath;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    public function getSize()
    {
        return filesize($this->getFilePath());
    }
}