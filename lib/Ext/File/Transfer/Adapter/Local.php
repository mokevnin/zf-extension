<?php

class Ext_File_Transfer_Adapter_Local implements Ext_File_Transfer_Adapter_Interface
{
    public function upload(Ext_File_File $file)
    {
        $success = rename($file->getFilePath(), $this->getDestination());
        $result = array(
            'success' => $success,
            'file_path' => $this->getDestination()
        );

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