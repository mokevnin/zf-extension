<?php

class Ext_File_Transfer_Adapter_Local extends Ext_File_Transfer_Adapter_Abstract
{
    protected $_resultObjectClass = 'Ext_File_Transfer_Adapter_Result_Local';

    public function upload(Ext_File_Transfer_File $file)
    {
        $success = rename($file->getFilePath(), $this->getDestination());
        $result = $this->getResultObject();
        $result->setSuccess($success);
        $result->setFilePath($this->getDestination());

        return $result;
    }

    public function setDestination($filepath)
    {
        $this->_destination = $filepath;
    }

    public function getDestination()
    {
        return $this->_destination;
    }
}