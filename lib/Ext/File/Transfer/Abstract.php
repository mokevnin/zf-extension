<?php

class Ext_File_Transfer_Abstract
{
     /**
     *
     * @var Ext_File_Interface
     */
    protected $_fsAccess;

    /**
     *
     * @var array
     */
    protected $_files = array();

    public function __construct(Ext_File_Interface $fsAccess)
    {
        $this->_fsAccess = $fsAccess;
    }

    public function transfer($files = null)
    {
        if (!$this->isValid($files)) {
            return false;
        }

        $files = $this->getFiles($files);
        foreach ($files as $file) {
            if ($file->isTransfered()) {
                continue;
            }
            if (!$file->isFiltered()) {
                $file->filter();
            }
            $result = $this->_fsAccess->create($file->getFilePath(), $file->getDestination());
            $file->setTransfered();
            if (!$result) {
                return false;
            }
        }

        return true;
    }
    
    public function setDestination($filePath)
    {
        foreach ($this->getFiles() as $file) {
            $file->setDestination($filePath);
        }
    }

    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false, $options = null, $files = null)
    {
        //TODO
    }

    public function isValid($files = null)
    {
        $check = $this->getFiles($files);
        foreach ($check as $file) {
            if (!$file->isValid()) {
                return false;
            }
        }
        return true;
    }
}