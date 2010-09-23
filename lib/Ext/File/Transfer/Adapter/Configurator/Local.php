<?php

class Ext_File_Transfer_Adapter_Configurator_Local extends Ext_File_Transfer_Adapter_Configurator_Abstract
{
    public function configure($filePath)
    {
        $this->getAdapter()->setFilePath(basename($filePath));
    }
}