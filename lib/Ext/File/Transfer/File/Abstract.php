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
        $this->_destination = $dest;
        $this->_newFileName = $newFileName;

        return $this;
    }

    public function getDestination()
    {
        $destionation = $this->_destination;
        if (!$destionation) {
            throw new Ext_File_Transfer_File_Exception('Destination is not set');
        } else if ($this->_newFileName) {
            $destionation = rtrim($destination, '/') . '/' . $this->_newFileName;
        }

        return $destionation;
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