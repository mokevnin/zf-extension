<?php

class Ext_File_WebdavTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Ext_Webdav_Client
     */
    public $client;
    public $fs;
    public $config;

    public function setUp()
    {
        $this->config = array(
            'client' => array(
                'host' => 'example.com', // required
                'schema' => 'http' // required
            )
        );
        $this->client = new Ext_Webdav_Client($this->config);
        $this->fs = new Ext_File_Webdav($this->client);
    }

    public function testGetClient()
    {
        $this->assertEquals($this->client, $this->fs->getClient());
    }
}