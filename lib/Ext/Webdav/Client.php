<?php

class Ext_Webdav_Client extends Zend_Http_Client
{
    const MKCOL    = 'MKCOL';
    const COPY     = 'COPY';
    const MOVE     = 'MOVE';
    const PROPFIND = 'PROPFIND';

    public function  __construct(array $config)
    {
        parent::__construct(null, $config);
        $this->setHeaders('host', $this->config['host']);
    }

    public function setUri($uri)
    {
        $full_uri = $this->config['schema'] . '://'
            . $this->config['host'] . '/'
            . ltrim($uri, '/\\');

        return parent::setUri($full_uri);
    }
}