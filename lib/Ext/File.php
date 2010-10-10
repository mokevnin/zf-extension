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

    protected $_configurator;

    protected $_transfered = false;

    protected $_result;

    public function  __construct($filePath = null, array $params = array())
    {
        $this->_filePath = $filePath;
        $this->setParams($params);
    }

    public function setTransfered($result)
    {
        $this->_transfered = (boolean) $result;
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
     * @see Zend_Validate_File_Upload (isValid)
     */
    public function offsetGet($name)
    {
        if (!isset($this->_params[$name])) {
            throw new Ext_File_Exception("Param '$name' does not exist");
        }

        return $this->_params[$name];
    }

    /**
     *
     * @param Zend_Validate_Interface $validator
     * @param boolean $breakChainOnFailure
     * @return Ext_File
     */
    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false)
    {
        $class = get_class($validator);
        $this->_validators[$class] = $validator;
        $this->_break[$class] = $breakChainOnFailure;
        $this->_validated = false;

        return $this;
    }

    /**
     *
     * @param string $className
     * @return Ext_File
     */
    public function removeValidator($className)
    {
        if (!isset($this->_validators[$className])) {
            throw new Ext_File_Transfer_Exception("Validator '$className' was not added");
        }
        unset($this->_validators[$className]);
        return $this;
    }

    /**
     *
     * @param Zend_Filter_Interface $filter
     * @return Ext_File 
     */
    public function addFilter(Zend_Filter_Interface $filter)
    {
        $class = get_class($filter);
        $this->_filters[$class] = $filter;

        return $this;
    }

    /**
     *
     * @param string $className
     * @return Ext_File
     */
    public function removeFilter($className)
    {
        if (!isset($this->_filters[$className])) {
            throw new Ext_File_Transfer_Exception("Filter '$className' was not added");
        }
        unset($this->_filters[$className]);

        return $this;
    }

    /**
     *
     * @return boolean
     */
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

    /**
     *
     * @return boolean
     */
    public function exists()
    {
        return is_readable($this->getFilePath());
    }

    /**
     *
     * @return Ext_File 
     */
    public function filter()
    {
        if ($this->isFiltered()) {
            return true;
        }

        foreach ($this->_filters as $filter) {
            $filter->filter($this->getFilePath());
        }
        $this->_filtered = true;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isFiltered()
    {
        return $this->_filtered;
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

    /**
     * @param Ext_File_Configurator $configurator
     */
    public function setConfigurator(Ext_File_Configurator_Abstract $configurator)
    {
        $this->_configurator = $configurator;
    }

    /**
     *
     * @return Ext_File_Configurator
     */
    public function getConfigurator()
    {
        return $this->_configurator;
    }
}