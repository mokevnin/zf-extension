<?php

class Ext_File_Transfer_File_HttpPost extends Ext_File_Transfer_File_Abstract
{
    protected $_options = array();
    protected $_formName;
    

    public function  __construct($formName, array $options)
    {
        $this->_formName = $formName;
        $this->setOptions($options);
        parent::__construct($options['tmp_name']);
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

    public function getFileId()
    {
        return $this->_formName;
    }

    public function setDestination($dest, $newFileName = null)
    {
        $new_file_name = $newFileName;
        if (!$new_file_name) {
            $new_file_name = $this->_options['name'];
        }

        return parent::setDestination($dest, $new_file_name);
    }
}