<?php

class Ext_File_Transfer_Adapter_Webdav extends Ext_File_Transfer_Adapter_Abstract
{
    /**
     * @var Ext_Webdav_Client
     */
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

    /**
     *
     * @param Ext_Webdav_Client $client
     * @return Ext_File_Transfer_Adapter_Webdav 
     */
    public function setClient(Ext_Webdav_Client $client)
    {
        $this->_client = $client;
        return $this;
    }

    /**
     * @return Ext_Webdav_Client
     */
    public function getClient()
    {
        return $this->_client;
    }
}