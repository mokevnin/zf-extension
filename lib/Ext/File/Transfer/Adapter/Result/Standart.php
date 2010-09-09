<?php

class Ext_File_Transfer_Adapter_Result_Standart extends ArrayObject
{
    private $_success = false;

    public function setSuccess($result)
    {
        $this->_success = (bool) $result;
    }

    public function isSuccess()
    {
        return $this->_success;
    }
}