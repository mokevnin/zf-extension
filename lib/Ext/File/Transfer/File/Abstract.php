<?php

abstract class Ext_File_Transfer_File_Abstract extends Ext_File
{
    protected $_destination;
    protected $_newFileName;

    protected $_validators = array();
    protected $_filters = array();
    
    protected $_filtered = false;
    protected $_transfered = false;
    protected $_validated = false;

    abstract function getFileId();

    public function setDestination($dest, $newFileName = null)
    {
        $this->_newFileName = $newFileName;
        $this->_destination = $destionation;

        return $this;
    }

    public function getDestination()
    {
        if (!$this->_destination) {
            throw new Ext_File_Transfer_Exception('Destination is not set');
        } else if ('/' == substr($this->_destination, -1, 1)) {
            $this->setDestination($this->_destination . $this->_options['name']);
        }

        return $this->_destination;
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

    public function isFiltered()
    {
        return $this->_filtered;
    }

    /**
     * @todo
     */
    public function filter()
    {
        if ($this->_transfered) {
            throw new Ext_File_Exception('File already transfered');
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