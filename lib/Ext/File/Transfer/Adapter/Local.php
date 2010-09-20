<?php

class Ext_File_Transfer_Adapter_Local extends Ext_File_Transfer_Adapter_Abstract
{
    public function upload($filepath)
    {
        if (!rename($filepath, $this->getDestination())) {
            throw new Ext_File_Transfer_Adapter_Exception("Can't move file to '{$this->getDesination()}'");
        }

        return $this->getDestination();
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