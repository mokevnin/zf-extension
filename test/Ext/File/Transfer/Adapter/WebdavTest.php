<?php

class Ext_File_Transfer_Adapter_WebdavTest extends PHPUnit_Framework_TestCase
{
    public function example()
    {
        // В случае с одним файлом и одним фильтром удобнее работать без трансфера
        $adapter = $transfer->getAdapter();
        $filepath = $adapter->download($file_id); //download
        $filter = new Filter_File_ImageCrop($params);
        $filter->filter($filepath);
        $result = $adapter->upload($filepath);
    }
}