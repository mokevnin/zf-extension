<?php

class Ext_Webdav_Client extends Zend_Http_Client
{
    const MKCOL = 'MKCOL';

    public function  __construct($config)
    {
        parent::__construct(null, $config);
        $this->setHeaders('host', $this->config['client']['host']);
    }

    public function setUri($uri)
    {
        $full_uri = $this->config['client']['schema'] . '://'
            . $this->config['client']['host'] . '/'
            . ltrim($uri, '/\\');

        return parent::setUri($full_uri);
    }
}