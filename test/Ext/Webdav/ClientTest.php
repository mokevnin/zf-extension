<?php

class Ext_Webdav_ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Ext_Webdav_Client
     */
    public $client;
    public $config;

    public function setUp()
    {
        $this->config = array(
            'host' => 'example.com', // required
            'schema' => 'http' // required
        );
        $this->client = new Ext_Webdav_Client($this->config);
    }

    public function testSetUri()
    {
        $uri = 'test/uri';
        $expected = $this->config['schema'] . '://'
            . $this->config['host'] . ':80/'
            . ltrim($uri, '/\\');

        $this->client->setUri($uri);
        $this->assertEquals($expected, $this->client->getUri(true));
    }
}