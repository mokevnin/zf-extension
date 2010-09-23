<?php

abstract class Ext_File_Transfer_Adapter_Abstract implements Ext_File_Transfer_Adapter_Interface
{
    private $_configurator;

    public function setConfigurator(Ext_File_Transfer_Adapter_Configurator_Interface $configurator)
    {
        $configurator->setAdapter($this);
        $this->_configurator = $configurator;
    }

    /**
     *
     * @return Ext_File_Transfer_Adapter_Configurator_Interface
     */
    public function getConfigurator()
    {
        if (empty($this->_configurator)) {
            throw new Ext_File_Transfer_Adapter_Exception('Set up you configurator');
        }
        
        return $this->_configurator;
    }

    public function configuredUpload($filepath)
    {
        $this->getConfigurator()->configure($filepath);
        $result = $this->upload($filepath);

        return $this->getConfigurator()->getResult($result);
    }
}