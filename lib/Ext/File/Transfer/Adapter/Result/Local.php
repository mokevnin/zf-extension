<?php

class Ext_File_Transfer_Adapter_Result_Local extends Ext_File_Transfer_Adapter_Result_Standart
{
    private $_filePath;

    public function setFilePath($filepath)
    {
        $this->_filePath = $filepath;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    public function __toString()
    {
        return $this->getFilePath();
    }
}