<?php

class Ext_File_Transfer_File
{
    protected $_options = array();
    protected $_formName;

    protected $_messages = array();
    protected $_validators = array();
    protected $_break = array();
    protected $_validated = false;
    
    protected $_filters = array();
    protected $_filtered = false;
    
    protected $_transfered = false;

    public function  __construct($formName, array $options)
    {
        $this->_formName = $formName;
        $this->setOptions($options);
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
     * @return Zend_File_Transfer_Adapter
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