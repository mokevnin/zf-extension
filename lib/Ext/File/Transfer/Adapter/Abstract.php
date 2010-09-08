<?php

class Ext_File_Transfer_Adapter_Abstract implements Ext_File_Transfer_Adapter_Interface
{
    protected $_resultObjectClass = 'Ext_File_Transfer_Adapter_Result_Standart';

    /**
     *
     * @return Ext_File_Transfer_Adapter_Result_Standart
     */
    public function getResultObject()
    {
        return new $this->_resultObjectClass();
    }
}