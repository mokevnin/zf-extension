<?php

class Ext_File_Configurator_HttpPost extends Ext_File_Configurator_Abstract
{
    public function configure(Ext_File $file)
    {
        $this->getAdapter()->setFilePath($file['name']);
    }
}