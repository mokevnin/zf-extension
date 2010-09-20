<?php

class Ext_File_Transfer
{
    /**
     *
     * @var Ext_File_Transfer_Adapter_Interface
     */
    protected $_adapter;
    
    protected $_files = array();
    protected $_fileClass = 'Ext_File';

    public function __construct(Ext_File_Transfer_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
        $this->_prepareFiles();
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
        $selected = $this->getFiles($files);
        $results = array();
        foreach ($selected as $file_key => $file) {
            $file->filter();
            if ($file->isValid() && !$file->isTransfered() && $file->exists()) {
                $file->setResult($this->getAdapter()->upload($file->getFilePath()));
                $file->setTransfered(true);
            }
            $results[$file_key] = $file;
        }

        return $results;
    }

    public function getFiles($files = null)
    {
        if (is_string($files)) {
            $files = array($files);
        }

        $selected = array();
        $check = sizeof($files) ? $files : array_keys($this->_files);
        foreach ($check as $file) {
            if ($file instanceof $this->_fileClass) {
                $file = $file->getFormName();
            }
            if ($file && !array_key_exists($file, $this->_files)) {
                $this->_files[$file] = new $this->_fileClass($file);
                //TODO add message to $file
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

    /**
     *
     * @param string $file
     * @return Ext_File
     */
    public function getFile($file)
    {
        return current($this->getFiles($file));
    }

    public function addFile($fileKey, Ext_File $file)
    {
        $this->_files[$fileKey] = $file;
        return $this;
    }

    protected function _prepareFiles()
    {
        foreach ($_FILES as $form_name => $options) {
            if (!is_array($options['name'])) {
                $this->_files[$form_name] = new $this->_fileClass($options['tmp_name'], $options);
            } else {
                $files = array();
                foreach ($options as $option_name => $values) {
                    foreach ($values as $index => $value) {
                        $new_form_name = $form_name . '_' . $index;
                        $files[$new_form_name][$option_name] = $value;
                    }
                }
                foreach ($files as $new_form_name => $options) {
                    $file = new $this->_fileClass($options['tmp_name'], $options);
                    $this->_files[$new_form_name] = $file;
                    $this->_files[$form_name][$new_form_name] = $file;
                }
            }
        }
    }
}