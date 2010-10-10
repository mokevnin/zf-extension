<?php

abstract class Ext_File_Configurator_Abstract
{
    /**
     * @var Ext_File_Adapter_Interface
     */
    private $_adapter;

    /**
     *
     * @param Ext_File_Adapter_Interface $adapter
     * @return Ext_File_Transfer
     */
    public function setAdapter(Ext_File_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * @return Ext_File_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    abstract function configure(Ext_File $file);
}