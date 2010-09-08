<?php

interface Ext_File_Transfer_Adapter_Interface
{
    /**
     * Return array
     */
    public function upload(Ext_File_File $file);
}