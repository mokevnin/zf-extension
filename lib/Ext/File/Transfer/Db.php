<?php

class Ext_File_Transfer_Db implements Ext_File_Transfer_Interface
{
    public function setDbAdapter()
    {

    }

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter()
    {
        
    }

    public function setFieldName()
    {
        
    }
    
    public function setTable()
    {
        
    }

    public function _doQuery($content)
    {

    }

    public function upload(Ext_File $file)
    {
        return $this->_doQuery(file_get_contents($file->getFilePath()));
    }
}