<?php

abstract class Ext_File_Transfer_Adapter_Configurator_Abstract implements Ext_File_Transfer_Adapter_Configurator_Interface
{
    /**
     * @var Ext_File_Transfer_Adapter_Interface
     */
    private $_adapter;

    public function setAdapter(Ext_File_Transfer_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * @return Ext_File_Transfer_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function getResult($result)
    {
        return $result;
    }
}