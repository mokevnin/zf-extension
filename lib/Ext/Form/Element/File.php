<?php

class Ext_Form_Element_File extends Zend_Form_Element_File
{
    /**
     * @var Ext_File_Transfer
     */
    private static $_transfer;

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (!$this->isValid(null)) {
            return null;
        }

        if ($this->isArray()) {
            return $this->getTransfer()->transfer($this->getName());
        }
        
        return current($this->getTransfer()->transfer($this->getName()));
    }

    public function addFilter($filter, $options = null)
    {
        $this->getTransfer()->addFilter($filter, $this->getName());

        return $this;
    }

    public function addValidator($validator, $breakChainOnFailure = false, $options = array())
    {
        $this->getTransfer()->addValidator($validator, $breakChainOnFailure, $this->getName());

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

    /**
     *
     * @param Ext_File_Transfer $transfer 
     */
    public static function setTransfer(Ext_File_Transfer $transfer)
    {
        $validator = new Ext_Validate_File_Post($transfer->getFiles());
        $transfer->addValidator($validator, false);
        self::$_transfer = $transfer;
    }

    public function setRequired($flag = true)
    {
        if ($flag) {
            $validator = new Ext_Validate_File_Upload($this->getTransfer()->getFiles());
            $this->getTransfer()->addValidator($validator, false, $this->getName());
        }

        return parent::setRequired($flag);
    }

    /**
     *
     * @return Ext_File_Transfer
     */
    public function getTransfer()
    {
        if (!self::$_transfer) {
            throw new Zend_Form_Element_Exception('Set up transfer to file element');
        }

        return self::$_transfer;
    }

    public function setConfigurator(Ext_File_Configurator_Abstract $configurator)
    {
        $this->getTransfer()->setConfigurator($configurator, $this->getName());
    }
}