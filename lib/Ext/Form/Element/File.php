<?php

class Ext_Form_Element_File extends Zend_Form_Element_File
{
    public function getValue()
    {
        if ($this->_value !== null) {
            return $this->_value;
        }

        $results = $this->getTransfer()->transfer($this->getName());

        if (sizeof($results) == 1) {
            return current($results);
        }

        return $results;
    }

    public function setTransfer(Ext_File_Transfer $adapter)
    {
        $this->_transfer = $transfer;
        
        return $this;
    }
}