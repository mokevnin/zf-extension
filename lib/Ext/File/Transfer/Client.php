<?php
/**
 * Работает с файлс
 */
class Ext_File_Transfer_Client extends Ext_File_Transfer_Abstract
{
    /**
     *
     * @var array
     */
    protected $_files = array();

    public function __construct(Ext_File_Interface $fsAccess)
    {
        parent::__construct($fsAccess);

        $this->_fsAccess = $fsAccess;
        $this->_prepareFiles();
        $this->addValidator('Upload', false, $this->_files);
    }

    public function addValidator($validator, $breakChainOnFailure = false, $options = null, $files = null)
    {

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

        $check = array();
        if (sizeof($files)) {
            foreach ($files as $file) {
                if ($file instanceof Ext_File_Transfer_File) {
                    $file = $file->getFileId();
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
            if (!is_array($options['name'])) {
                $this->_files[$form] = new Ext_File_Transfer_File($form, $options);
            } else {
                //TODO
            }
        }
    }
}