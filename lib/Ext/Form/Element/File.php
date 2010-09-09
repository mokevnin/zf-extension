<?php

class Ext_Form_Element_File extends Zend_Form_Element_File
{
    /**
     *
     * @var Ext_File_Transfer
     */
    private static $_transfer;
    
    public function getValue()
    {
        if (!$this->isValid(null)) {
            return null;
        }

        $results = $this->getTransfer()->transfer($this->getName());

        if (!$results) {
            return null;
        }

        if (sizeof($results) == 1) {
            return current($results);
        }

        return $results;
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