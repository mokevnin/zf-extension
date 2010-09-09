<?php

class Ext_File_Transfer
{
    /**
     *
     * @var Ext_File_Transfer_Adapter_Interface
     */
    protected $_adapter;
    
    protected $_files = array();

    public function __construct(Ext_File_Transfer_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
        $this->_prepareFiles();
        $this->addValidator(new Zend_Validate_File_Upload(), false);
    }

    public function isValid($files = null)
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            if (!$file->isValid()) {
                return false;
            }
        }
        return true;
    }

    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->addValidator($validator, $breakChainOnFailure);
        }
    }

    public function removeValidator($className, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->removeValidator($className);
        }

        return $this;
    }

    public function addFilter(Zend_Filter_Interface $filter, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->addFilter($filter);
        }
    }

    /**
     *
     * @return Ext_File_Transfer_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function transfer($files = null)
    {
        if (!$this->isValid($files)) {
            return false;
        }

        $selected = $this->getFiles($files);
        $results = array();
        foreach ($selected as $file) {
            $file->filter();
            if ($file->isTransfered()) {
                continue;
            }
            $result[$file->getFormName()] = $this->_doTransfer($file);
        }

        return $result;
    }

    public function _doTransfer(Ext_File_Transfer_File $file)
    {
        return $this->getAdapter()->upload($file);
    }

    public function getFiles($files = null)
    {
        if (is_string($files)) {
            $files = array($files);
        }

        $selected = array();
        $check = sizeof($files) ? $files : array_keys($this->_files);
        foreach ($check as $file) {
            if ($file instanceof Ext_File_Transfer_File) {
                $file = $file->getFormName();
            }
            if ($file && !array_key_exists($file, $this->_files)) {
                $this->_files[$file] = new Ext_File_Transfer_File($file);
                //throw new Ext_File_Transfer_Exception("File '$file' does not exist");
            }
            if (is_array($this->_files[$file])) {
                if (sizeof($files) == 1) {
                    return $this->_files[$file];
                } else {
                    continue;
                }
            }
            $selected[$file] = $this->_files[$file];
        }

        return $selected;
    }

    public function getFile($file)
    {
        return current($this->getFiles($file));
    }

    protected function _prepareFiles()
    {
        if (!isset($_FILES) || !sizeof($_FILES)) {
            return;
        }

        foreach ($_FILES as $form_name => $options) {
            if (!is_array($options['name'])) {
                $this->_files[$form_name] = new Ext_File_Transfer_File($form_name, $options);
            } else {
                $files = array();
                foreach ($options as $option_name => $values) {
                    foreach ($values as $index => $value) {
                        $new_form_name = $form_name . '_' . $index;
                        $files[$new_form_name][$option_name] = $value;
                    }
                }
                foreach ($files as $new_form_name => $options) {
                    $file = new Ext_File_Transfer_File($form_name, $options);
                    $this->_files[$new_form_name] = $file;
                    $this->_files[$form_name][$new_form_name] = $file;
                }
            }
        }
    }
}