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
        if ($this->_value !== null) {
            return $this->_value;
        }

        $results = self::$_transfer->transfer($this->getName());

        if (sizeof($results) == 1) {
            return current($results);
        }

        return $results;
    }

    public static function setTransfer(Ext_File_Transfer $transfer)
    {
        self::$_transfer = $transfer;
    }

    public function getAdapter()
    {
        return self::$_transfer->getAdapter();
    }
}