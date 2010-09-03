<?php

class Ext_File_Transfer_File extends Ext_File
{
    protected $_options = array();
    protected $_formName;
    protected $_destination;

    public function  __construct($formName, array $options)
    {
        $this->setOptions($options);
        parent::__construct($options['tmp_name']);
        $this->_formName = $formName;
    }

    public function setOptions(array $options)
    {
        if (!array_key_exists('error', $options)
          || !array_key_exists('tmp_name', $options)
          || !array_key_exists('name', $options)) {
            throw new Exception('Options does not valid');
        }
        $this->_options = $options;

        return $this;
    }

    public function getFormName()
    {
        return $this->_formName;
    }

    public function isValid()
    {
        return !$this->_options['error'];
    }

    public function getErrorCode()
    {
        return $this->_options['error'];
    }

    public function setDestination($dest)
    {
        $this->_destination = $dest;

        return $this;
    }

    public function getDestination()
    {
        if (!$this->_destination) {
            throw new Ext_File_Transfer_Exception('Destination is not set');
        } else if ('/' == substr($this->_destination, -1, 1)) {
            $this->setDestination($this->_destination . $this->_options['name']);
        }
        
        return $this->_destination;
    }
}