<?php

class Ext_File_Local extends Ext_File_Abstract
{
    protected function  _doTransfer(Ext_File $file)
    {
        $this->getReciver()->upload($file);
    }
}