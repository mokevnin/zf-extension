<?php

interface Ext_File_Transfer_Adapter_Configurator_Interface
{
    public function getResult($result);
    public function configure($filePath);
}