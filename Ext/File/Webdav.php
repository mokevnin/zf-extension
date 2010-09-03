<?php

class Ext_File_Webdav implements Ext_File_Interface
{
    /**
     *
     * @var Ext_Webdav_Client
     */
    protected $_client;

    public function setClient(Ext_Webdav_Client $client)
    {
        $this->_client = $client;

        return $this;
    }

    public function mkDir($path, $mode = 0777, $recursive = false)
    {
        if (!$this->_client) {
            throw new Ext_File_Transfer_Adapter_Exception("Default client does not exist");
        }
        if (!$recursive) {
            $this->_client->setUri($path);
            return $this->_client->request(Ext_Webdav_Client::MKCOL);
        } else {
            $parts = explode('/', $path);
            $dir = '';
            foreach ($parts as $part) {
                $dir .= '/' . $part;
                $this->_client->setUri($dir);
                $this->_client->request(Ext_Webdav_Client::MKCOL);
            }
        }
    }

    public function isDir($pathname)
    {
        return false;
    }

    public function rename($src, $dest)
    {
        
    }

    public function copy($src, $dest)
    {
        
    }
    
    public function create($src, $dest)
    {
        return $this->_doCreate($src, $dest, $this->_client);
    }

    protected function _doCreate($src, $dest, Ext_Webdav_Client $client)
    {
        $file_path = ($src instanceof Ext_File) ? $src->getFilePath() : $src;

        $client->setHeaders('Content-Type', 'application/octet-stream');
        $client->setHeaders('Accept-encoding', 'identity');
        $client->setUri($dest);
        $client->setRawData(file_get_contents($src));

        $response = $client->request(Zend_Http_Client::PUT);
        if (201 != $response->getStatus()) {
            trigger_error('Response code for mkcol was: ' . $response->getStatus(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    protected function _checkClient($clientName)
    {
        if (!array_key_exists($clientName, $this->_clients)) {
            throw new Ext_File_Transfer_Adapter_Exception("Client '$clientName' does not exist");
        }
    }
}