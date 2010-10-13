<?php

class Ext_File_Transfer
{
    /**
     *
     * @var Ext_File_Adapter_Interface
     */
    protected $_adapter;

    /**
     * @var array
     */
    protected $_files = array();

    protected $_fileClass = 'Ext_File';

    /**
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->_prepareFiles();
    }

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
     *
     * @param mixed $files
     * @return boolean
     */
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

    /**
     *
     * @param Zend_Validate_Interface $validator
     * @param boolean $breakChainOnFailure
     * @param mixed $files
     * @return Ext_File_Transfer
     */
    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->addValidator($validator, $breakChainOnFailure);
        }
        return $this;
    }

    /**
     *
     * @param string $className
     * @param mixed $files
     * @return Ext_File_Transfer
     */
    public function removeValidator($className, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->removeValidator($className);
        }
        return $this;
    }

    /**
     *
     * @param Zend_Filter_Interface $filter
     * @param mixed $files
     * @return Ext_File_Transfer
     */
    public function addFilter(Zend_Filter_Interface $filter, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->addFilter($filter);
        }
        return $this;
    }

    /**
     *
     * @param Ext_File_Configurator_Abstract $configurator
     * @param mixed $files
     * @return Ext_File_Transfer
     */
    public function setConfigurator(Ext_File_Configurator_Abstract $configurator, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->setConfigurator($configurator);
        }
        return $this;
    }

    /**
     *
     * @param string $className
     * @param mixed $files
     * @return Ext_File_Transfer
     */
    public function removeFilter($className, $files = array())
    {
        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            $file->removeFilter($className);
        }
        return $this;
    }

    /**
     *
     * @return Ext_File_Adapter_Interface
     */
    public function getAdapter()
    {
        if (!$this->_adapter) {
            $this->_adapter = new Ext_File_Adapter_Local();
        }

        return $this->_adapter;
    }

    /**
     *
     * @param mixed $files
     * @return array
     */
    public function transfer($files = null)
    {
        $selected = $this->getFiles($files);
        $results = array();
        foreach ($selected as $file_key => $file) {
            $file->filter();
            $file->getConfigurator()
                ->setAdapter($this->getAdapter())
                ->configure($file);
            if ($file->isValid() && !$file->isTransfered() && $file->exists()) {
                $result = $this->getAdapter()->upload($file->getFilePath());
                $file->setTransfered(true);
            }
            $results[$file_key] = $result;
        }

        return $results;
    }

    /**
     *
     * @param mixed $files
     * @return array
     */
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