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

    public function getClient()
    {
        if (!$this->_client) {
            throw new Exception('Webdav client is not set');
        }
        return $this->_client;
    }

    public function mkDir($path, $mode = 0777, $recursive = false)
    {
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
        $this->_client->setUri($src);
        $this->_client->setHeaders('Destination', $dest);
        $this->_client->setHeaders('Overwrite', 'T');
        $response = $this->_client->request(Ext_Webdav_Client::MOVE);
        if (201 != $response->getStatus()) {
            trigger_error("Response code for MOVE was: " . $response->getStatus(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    public function copy($src, $dest)
    {
        $this->_client->setUri($src);
        $this->_client->setHeaders('Destination', $dest);
        $this->_client->setHeaders('Overwrite', 'T');
        $response = $this->_client->request(Ext_Webdav_Client::COPY);
        if (201 != $response->getStatus()) {
            trigger_error("Response code for COPY was: " . $response->getStatus(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    public function delete($filepath)
    {
        $this->_client->setUri($filepath);
        $this->_client->setHeaders('Content-Length', 0);
        $response = $this->_client->request(Zend_Http_Client::DELETE);
        if (200 != $response->getStatus()) {
            trigger_error("Response code for DELETE was: " . $response->getStatus(), E_USER_WARNING);
            return false;
        }

        return true;
    }
    
    public function create($src, $dest)
    {
        $file_path = ($src instanceof Ext_File) ? $src->getFilePath() : $src;

        $this->_client->setHeaders('Content-Type', 'application/octet-stream');
        $this->_client->setHeaders('Accept-encoding', 'identity');
        $this->_client->setUri($dest);
        $this->_client->setRawData(file_get_contents($src));

        $response = $this->_client->request(Zend_Http_Client::PUT);
        if (201 != $response->getStatus()) {
            trigger_error("Response code for MKCOL was: " . $response->getStatus(), E_USER_WARNING);
            return false;
        }

        return true;
    }
}