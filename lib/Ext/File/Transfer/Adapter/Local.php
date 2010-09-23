<?php

class Ext_File_Transfer_Adapter_Local extends Ext_File_Transfer_Adapter_Abstract
{
    private $_destination;
    private $_filePath;

    public function upload($srcFilePath)
    {
        if (!rename($srcFilePath, $this->getFullFilePath())) {
            throw new Ext_File_Transfer_Adapter_Exception("Can't move file to '{$this->getFullFilePath()}'");
        }

        return $this->getFilePath();
    }

    public function setDestination($filepath)
    {
        $this->_destination = rtrim($filepath, '/\\');
    }

    public function getDestination()
    {
        return $this->_destination;
    }

    public function getFullFilePath()
    {
        return $this->getDestination() . '/' . $this->getFilePath();
    }

    public function setFilePath($filepath)
    {
        $this->_filePath = $filepath;

        return $this;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }
}