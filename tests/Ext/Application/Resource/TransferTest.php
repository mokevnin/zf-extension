<?php

class Ext_Application_Resource_TransferTest extends PHPUnit_Framework_TestCase
{
    private $_resource;

    public function  setUp()
    {
        $this->_resource = new Ext_Application_Resource_Transfer();
    }

    public function testInit()
    {
        $this->assertTrue($this->_resource->init() instanceof Ext_File_Transfer);
    }

    public function testInitWithOptions()
    {
        $options = array(
            'adapter' => array(
                'params' => array(
                    'configurator' => 'Ext_File_Transfer_Adapter_Configurator_Local',
                    'wrong_key' => 'value',
                    'destination' => 'test/destination'
                )
            )
        );

        $this->_resource->setOptions($options);
        $transfer = $this->_resource->getTransfer();
        $destination = $transfer->getAdapter()->getDestination();
        $this->assertEquals($options['adapter']['params']['destination'], $destination);
    }
}