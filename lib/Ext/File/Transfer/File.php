<?php

class Ext_File_Transfer_File
{
    protected $_options = array();
    protected $_formName;

    protected $_validators = array();
    protected $_filters = array();

    protected $_filtered = false;
    protected $_validated = false;
    protected $_result = array();
    protected $_transfered = false;

    public function  __construct($formName, array $options)
    {
        $this->_formName = $formName;
        $this->setOptions($options);
    }

    public function setResult(array $resultOptions)
    {
        $this->_result = $resultOptions;
        $this->_transfered = true;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function isTransfered()
    {
        return $this->_transfered;
    }

    public function getFilePath()
    {
        return $this->_options['tmp_name'];
    }

    public function getFormName()
    {
        return $this->_formName;
    }

    public function setOptions(array $options)
    {
        if (!array_key_exists('error', $options)
          || !array_key_exists('tmp_name', $options)
          || !array_key_exists('name', $options)) {
            throw new Ext_File_Transfer_File_Exception('Options does not valid');
        }
        $this->_options = $options;

        return $this;
    }

    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false, $options = null, $files = null)
    {
        //TODO
    }

    public function isValid()
    {
        if ($this->_validated) {
            return true;
        }

        //TODO
        return true;
    }

    public function addFilter(Zend_Filter_Interface $filter)
    {
        //TODO
    }

    public function filter()
    {
        if ($this->_filtered) {
            return true;
        }
        foreach ($this->_filters as $name => $filter) {
            $filter->filter($this->getFilePath());
        }
        $this->_filtered = true;
    }
}