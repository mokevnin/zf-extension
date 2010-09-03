<?php
/**
 * Работает с файлс
 */
class Ext_File_Transfer_Client
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
        $this->_prepareFiles();
    }

    /**
     *
     * @return Ext_File_Interface
     */
    public function getFsAccess()
    {
        return $this->_fsAccess;
    }

    public function setDestination($filePath)
    {
        foreach ($this->getFiles() as $file) {
            $file->setDestination($filePath);
        }
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

    public function isUploaded($files = null)
    {
        $check = $this->getFiles($files);
        foreach ($check as $file) {
            if (!$file->isUploaded()) {
                return false;
            }
        }
        return true;
    }
    
    public function create($files = null)
    {
        if (!$this->isValid($files)) {
            return false;
        }

        if (!$this->isUploaded($files)) {
            return false;
        }

        $files = $this->getFiles($files);
        foreach ($files as $file) {
            $result = $this->_fsAccess->create($file->getFilePath(), $file->getDestination());
            if (!$result) {
                return false;
            }
        }

        return true;
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

        $check = array();
        if (sizeof($files)) {
            foreach ($files as $file) {
                if ($file instanceof Ext_File_Transfer_File) {
                    $file = $file->getFormName();
                }
                if ($file && !array_key_exists($file, $this->_files)) {
                    throw new Ext_File_Transfer_Exception("File '$file' does not exist");
                }
                $check[$file] = $this->_files[$file];
            }
        }

        
        return sizeof($check) ? $check : $this->_files;
    }

    protected function _prepareFiles()
    {
        if (!isset($_FILES) || !sizeof($_FILES)) {
            return;
        }

        foreach ($_FILES as $form => $options) {
            $this->_files[$form] = new Ext_File_Transfer_File($form, $options);
        }
    }
}