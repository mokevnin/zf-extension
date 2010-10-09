<?php

abstract class Ext_File_Transfer_Adapter_Abstract implements Ext_File_Transfer_Adapter_Interface
{
    private $_configurator;

    public function  __construct(array $params = array())
    {
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'configurator':
                    $this->setConfigurator(new $value());
                    break;
                default:
                    $method = 'set' . ucfirst(mb_strtolower($key));
                    if (method_exists($this, $method)) {
                        $this->$method($value);
                    }
            }
        }

        if (!$this->_configurator) {
            $this->setConfigurator(new Ext_File_Transfer_Adapter_Configurator_Local());
        }
    }

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