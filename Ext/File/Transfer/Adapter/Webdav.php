<?php

class Ext_File_Transfer_Adapter_Webdav implements Ext_File_Transfer_Adapter_Interface
{
    /**
     *
     * @var Ext_Webdav_Client
     */
    protected $_defaultClient;

    protected $_clients = array();

    public function setDefaultClient(Ext_Webdav_Client $client)
    {
        $this->_defaultClient = $client;

        return $this;
    }

    public function addClient($name, Ext_Webdav_Client $client)
    {
        $this->_clients[$name] = $client;

        return $this;
    }

    public function uploadToClient($src, $dest, $clientName)
    {
        $this->_checkClient($clientName);
        return $this->_doUpload($src, $dest, $this->_clients[$clientName]);
    }

    public function create($src, $dest)
    {
        if (!$this->_defaultClient) {
            throw new Ext_File_Transfer_Adapter_Exception("Default client does not exist");
        }
        return $this->_doUpload($src, $dest, $this->_defaultClient);
    }

    protected function _doCreate($src, $dest, Ext_Webdav_Client $client)
    {
        $file_path = ($src instanceof Ext_File) ? $src->getFilePath() : $src;

        $client->setUri($dest);
        $client->setRawData(file_get_contents($src));

		return $client->request(Zend_Http_Client::PUT);
    }

    protected function _checkClient($clientName)
    {
        if (!array_key_exists($clientName, $this->_clients)) {
            throw new Ext_File_Transfer_Adapter_Exception("Client '$clientName' does not exist");
        }
    }
}