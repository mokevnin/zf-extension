<?php

class Ext_File_Transfer_Local implements Ext_File_Transfer_Interface
{
    public function upload(Ext_File $file)
    {
        return rename($file->getFilePath(), $this->getDestination());
    }

    public function setDestination($path, $filename)
    {

    }

    public function getDestination()
    {
        
    }
}