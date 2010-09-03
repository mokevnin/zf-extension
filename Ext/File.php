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

    public function isUploaded()
    {
        return is_uploaded_file($this->getFilePath());
    }

    public function getSize()
    {
        return sprintf("%u", filesize($this->getFilePath()));
    }
}