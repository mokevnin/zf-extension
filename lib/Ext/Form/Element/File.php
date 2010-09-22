<?php

class Ext_Form_Element_File extends Zend_Form_Element_File
{
    /**
     *
     * @var Ext_File_Transfer
     */
    private static $_transfer;

    protected static $_test = false;

    public static function initializeStub()
    {
        self::$_test = true;
    }

    public function getValue()
    {
        if (self::$_test) {
            $file = new Ext_File();
            $file->setTransfered(true);
            $file->setResult(Test_Object::getInstance()->addFile());
            return $file;
        }

        if (!$this->isValid(null)) {
            return null;
        }

        if($this->isArray()) {
            return $this->getTransfer()->transfer($this->getName());
        }
        return current($this->getTransfer()->transfer($this->getName()));
    }

    public function addFilter($filter, $options = null)
    {
        $this->getTransfer()->addFilter($filter, $this->getName());

        return $this;
    }

    public function isValid($value, $context = null)
    {
        if ($this->_validated) {
            return true;
        }

        if (!$this->isRequired()) {
            $files = $this->getTransfer()->getFiles($this->getName());
            foreach($files as $file) {
                $file->setIgnoreNoFile();
            }
        }
        if ($this->getTransfer()->isValid($this->getName())) {
            $this->_validated = true;
            return true;
        }

        $this->_validated = false;
        return false;
    }

    public function getErrors()
    {
        $file = $this->getTransfer()->getFile($this->getName());
        return parent::getErrors() + $file->getErrors();
    }

    /**
     * Are there errors registered?
     *
     * @return bool
     */
    public function hasErrors()
    {
        $file = $this->getTransfer()->getFile($this->getName());
        return (parent::hasErrors() || $file->hasErrors());
    }

    /**
     * Retrieve error messages; proxy to transfer adapter
     *
     * @return array
     */
    public function getMessages()
    {
        $file = $this->getTransfer()->getFile($this->getName());
        return parent::getMessages() + $file->getMessages();
    }

    public static function setTransfer(Ext_File_Transfer $transfer)
    {
        $validator = new Zend_Validate_File_Upload();
        $validator->setFiles($transfer->getFiles());
        $transfer->addValidator($validator, false);
        self::$_transfer = $transfer;
    }

    public function getTransfer()
    {
        return self::$_transfer;
    }

    public function getAdapter()
    {
        return $this->getTransfer()->getAdapter();
    }
}