<?php

abstract class Ext_File_Abstract
{
    /**
     *
     * @var Ext_File_Transfer_Interface
     */
    protected $_receiver;

    abstract protected function _doTransfer(Ext_File $file);

    public function __construct(Ext_File_Transfer_Interface $receiver)
    {
        $this->_receiver = $receiver;
        $this->_prepareFiles();
        //$this->addValidator('Upload', false, $this->_files);
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
     * @return Ext_File_Transfer_Interface
     */
    public function getReciver()
    {
        return $this->_receiver;
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
            $this->_doTransfer($file);
            $file->setTransfered();
        }

        return true;
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

    /**
     *
     * @param string $file
     * @return Ext_File_Transfer_File
     */
    public function getFile($file)
    {
        return current($this->getFiles($file));
    }

    protected function _prepareFiles()
    {
        if (!isset($_FILES) || !sizeof($_FILES)) {
            return;
        }

        foreach ($_FILES as $form_name => $options) {
            if (!is_array($options['name'])) {
                $this->_files[$form_name] = new Ext_File_Transfer_File_HttpPost($form_name, $options);
            } else {
                $files = array();
                foreach ($options as $option_name => $values) {
                    foreach ($values as $index => $value) {
                        $new_form_name = $form_name . '_' . $index;
                        $files[$new_form_name][$option_name] = $value;
                    }
                }
                foreach ($files as $new_form_name => $options) {
                    $file = new Ext_File_Transfer_File_HttpPost($form_name, $options);
                    $this->_files[$new_form_name] = $file;
                    $this->_files[$form_name][$new_form_name] = $file;
                }

            }
        }
    }
}