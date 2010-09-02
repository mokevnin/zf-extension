<?php
/**
 * Работает с файлс
 */
class Ext_File_Transfer
{
    /**
     *
     * @var Ext_File_Transfer_Adapter_Interface
     */
    protected $_adapter;

    /**
     *
     * @todo Поменять структуру
     * @var array
     */
    protected $_files;

    public function __construct($adapter)
    {
        $this->_adapter = $adapter;
        $this->_prepareFiles();
    }

    /**
     *
     * @return Ext_File_Transfer_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function receive($files = null)
    {
        foreach ($this->getFiles() as $info) {
            $file = $info['file'];
            if (!is_uploaded_file($file->getFilePath())) {
                throw new Ext_File_Transfer('Possible attack!');
            }
            //$this->_adapter->create($file, $dest); Что должно быть в $dest?
        }
    }

    public function getFiles($file = null)
    {
        if ($file && !array_key_exists($file, $this->_files)) {
            throw new Ext_File_Transfer_Exception("File '$file' does not exist");
        }
        return ($file) ? $this->_files[$file] : $this->_files;
    }

    protected function _prepareFiles()
    {
        $this->_files = array();
        foreach ($_FILES as $form => $content) {
            $this->_files[$form] = $content;
            $this->_files[$form]['file'] = new Ext_File($content['tmp_name']);
        }

        return $this;
    }
}