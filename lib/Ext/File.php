<?php

class Ext_File extends ArrayObject
{
    protected $_params = array();
    protected $_filePath;

    protected $_messages = array();
    protected $_validators = array();
    protected $_break = array();
    protected $_validated = false;

    protected $_filters = array();
    protected $_filtered = false;

    protected $_transfered = false;
    protected $_ignoreNoFile = false;

    protected $_result;

    public function  __construct($filePath = null, array $params = array())
    {
        $this->_filePath = $filePath;
        $this->setParams($params);
    }

    public function setResult($result)
    {
        $this->_result = $result;
        return $this;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function setTransfered($result)
    {
        $this->_transfered = (bool) $result;
    }

    public function isTransfered()
    {
        return $this->_transfered;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    public function setParams(array $params)
    {
        $this->_params = $params;

        return $this;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function offsetExists($name)
    {
        return isset($this->_params[$name]);
    }

    /**
     * @see Zend_Validate_File_Upload
     */
    public function offsetGet($name)
    {
        if (!isset($this->_params[$name])) {
            throw new Ext_File_Exception("Param '$name' does not exist");
        }

        return $this->_params[$name];
    }

    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false)
    {
        $class = get_class($validator);
        $this->_validators[$class] = $validator;
        $this->_break[$class] = $breakChainOnFailure;
        $this->_validated = false;

        return $this;
    }

    public function removeValidator($className)
    {
        if (!isset($this->_validators[$className])) {
            throw new Ext_File_Transfer_Exception("removeValidator //todo");
        }
        unset($this->_validators[$className]);
        return $this;
    }

    /**
     * Adds a new filter for this class
     *
     * @param  string|array $filter Type of filter to add
     * @param  string|array $options   Options to set for the filter
     * @param  string|array $files     Files to limit this filter to
     * @return Ext_File
     */
    public function addFilter(Zend_Filter_Interface $filter)
    {
        $class = get_class($filter);
        $this->_filters[$class] = $filter;

        return $this;
    }

    public function isValid()
    {
        if ($this->_validated) {
            return true;
        }

        foreach ($this->_validators as $class => $validator) {
            if (!$validator->isValid($this->getFilePath())) {
                $this->_messages += $validator->getMessages();
            }

            //TODO move into validator
            if ($this->ignoreNoFile() and (isset($this->_messages['fileUploadErrorNoFile']))) {
                unset($this->_messages['fileUploadErrorNoFile']);
                break;
            }

            if (($this->_break[$class]) and (sizeof($this->_messages) > 0)) {
                return false;
            }
        }
        if (sizeof($this->_messages) > 0) {
            return false;
        }

        $this->_validated = true;

        return true;
    }

    public function exists()
    {
        return is_readable($this->getFilePath());
    }

    public function setIgnoreNoFile($ignoreNoFile = true)
    {
        $this->_ignoreNoFile = (bool) $ignoreNoFile;

        return $this;
    }

    public function ignoreNoFile()
    {
        return $this->_ignoreNoFile;
    }

    public function filter()
    {
        if ($this->_filtered) {
            return true;
        }

        foreach ($this->_filters as $filter) {
            $filter->filter($this->getFilePath());
        }
        $this->_filtered = true;
    }

    /**
     * Returns found validation messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * Retrieve error codes
     *
     * @return array
     */
    public function getErrors()
    {
        return array_keys($this->_messages);
    }

    /**
     * Are there errors registered?
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return (!empty($this->_messages));
    }
}