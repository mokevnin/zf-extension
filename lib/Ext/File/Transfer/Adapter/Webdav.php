<?php

class Ext_File_Transfer_Adapter_Webdav implements Ext_File_Transfer_Adapter_Interface
{
    protected $_client;

    public function upload(Ext_File_File $file)
    {
        $this->_client->setHeaders('Content-Type', 'application/octet-stream');
        $this->_client->setHeaders('Accept-encoding', 'identity');
        $this->_client->setRawData(file_get_contents($file->getFilePath()));

        $response = $this->_client->request(Zend_Http_Client::PUT);

        $success = $response->getStatus() == 201 ? true : false;
        $result = array(
            'success' => $success,
            'status' => $response->getStatus()
        );
        
        return $result;
    }

    public function setUri($uri)
    {
        $this->getClient()->setUri($uri);
    }

    public function setClient(Ext_Webdav_Client $client)
    {
        $this->_client = $client;
    }

    public function getClient()
    {
        return $client;
    }
}