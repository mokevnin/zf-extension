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

// custom adapter
class File_Transfer_Adapter_DbWebdav extends Ext_File_Transfer_Adapter_Webdav
{
    public function upload($filepath)
    {
        $table = File::getInstance();
        $row = $table->createRowFromFilePath($filepath);
        $row->save();
        $this->setUri($this->generateUri($row->id));

        parent::upload($filepath);

        return $row;
    }

    public function download($fileId)
    {
        $this->setUri($this->generateUri($fileId));
        return parent::download();
    }

    public function generateUri($id)
    {
        $uri = substr($id, 0, 2) . '/' .
            substr($id, 2, 2) . '/' .
            substr($id, 4, 2) . '/' .
            $id;

        return $uri;
    }
}