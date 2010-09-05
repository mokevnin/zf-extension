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

        $selected = $this->getFiles($files);
        foreach ($selected as $file) {
            if ($file->isTransfered()) {
                continue;
            }
            $file->filter();
            if (!$this->_fsAccess->create($file->getFilePath(), $file->getDestination())) {
                return false;
            }
            $file->setTransfered();
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
     * @return Ext_File_Interface
     */
    public function getFsAccess()
    {
        return $this->_fsAccess;
    }

    /**
     *
     * @param string $file
     * @return Ext_File_Transfer_File
     */
    public function getFile($file)
    {
        return current($this->getFiles($file));
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
                $file = $file->getFileId();
            }
            if ($file && !array_key_exists($file, $this->_files)) {
                throw new Ext_File_Transfer_Exception("File '$file' does not exist");
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
}