<?php

class Ext_File_Transfer_Adapter_Webdav extends Ext_File_Transfer_Adapter_Abstract
{
    protected $_client;

    public function upload($filepath)
    {
        $this->_client->setHeaders('Content-Type', 'application/octet-stream');
        $this->_client->setHeaders('Accept-encoding', 'identity');
        $this->_client->setRawData(file_get_contents($filepath));

        $response = $this->_client->request(Zend_Http_Client::PUT);

        if ($response->getStatus() != 201) {
            throw new Ext_File_Transfer_Adapter_Exception("Status code was {$response->getStatus()}");
        }
        
        return $this->getClient()->getUri();
    }

    public function download()
    {
        $response = $this->getClient()->request();
        $file_name = tempnam(sys_get_temp_dir(), md5(mt_rand()));
        $result = file_put_contents($file_name, $response->getBody());
        if (!$result) {
            throw new Ext_File_Transfer_Adapter_Exception('todo');
        }

        return $file_name;
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
        return $this->_client;
    }
}