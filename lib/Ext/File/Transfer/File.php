<?php

class Ext_File_Transfer_File extends Ext_File_Transfer_File_Abstract
{
    protected $_options = array();
    protected $_formName;
    

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

    public function getFileId()
    {
        return $this->_formName;
    }
}