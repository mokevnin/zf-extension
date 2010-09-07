<?php

class Ext_File
{
    protected $_options = array();
    protected $_formName;

    protected $_validators = array();
    protected $_filters = array();

    protected $_filtered = false;
    protected $_transfered = false;
    protected $_validated = false;

    public function  __construct($formName, array $options)
    {
        $this->_formName = $formName;
        $this->setOptions($options);
    }

    public function getFilePath()
    {
        return $this->_options['tmp_name'];
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
    }

    public function addFilter(Zend_Filter_Interface $filter)
    {
        //TODO
    }

    /**
     * @todo
     */
    public function filter()
    {
        if ($this->_filtered) {
            return true;
        }
        foreach ($this->_filters as $name => $filter) {
            if ($name == 'Rename') {
                $this->_newFileName = $filter->getNewName();
            } else {
                $filter->filter();
            }
        }
        $this->_filtered = true;
    }

    public function setTransfered()
    {
        $this->_transfered = true;

        return $this;
    }

    public function isTransfered()
    {
        return $this->_transfered;
    }
}