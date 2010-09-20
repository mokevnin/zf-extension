<?php

class Ext_File_Transfer_Adapter_LocalTest extends PHPUnit_Framework_TestCase
{
    protected $_adapter;
    protected $_transfer;
    protected $_newFilePath = '/tmp/newfilename';

    public function setUp()
    {
        $tmpfname = tempnam("/tmp", "FOO");

        $handle = fopen($tmpfname, "w");
        fwrite($handle, $this->_newFilePath);
        fclose($handle);

        $_FILES = array(
            'formname' => array(
                'tmp_name' => $tmpfname,
                'name' => 'filename',
                'error' => 0,
            )
        );

        $this->_adapter = new Ext_File_Transfer_Adapter_Local();
        $this->_transfer = new Ext_File_Transfer($this->_adapter);
        Ext_Form_Element_File::setTransfer($this->_transfer);
    }

    public function testUpload()
    {
        $this->_transfer->removeValidator('Zend_Validate_File_Upload');
        $element = new Ext_Form_Element_File('formname');
        $element->addFilter(new Zend_Filter_File_UpperCase());

        $adapter = $element->getAdapter();
        $adapter->setDestination($this->_newFilePath);
        $value = $element->getValue('formname');
        
        $this->assertTrue($value->isTransfered());
        $this->assertTrue(is_readable($this->_newFilePath));

        //test filters
        $this->assertEquals(strtoupper($this->_newFilePath), file_get_contents($this->_newFilePath));
    }

    public function testUploadError()
    {
        $element = new Ext_Form_Element_File('formname');

        $adapter = $element->getAdapter();
        $adapter->setDestination($this->_newFilePath);
        $value = $element->getValue('formname');

        $this->assertEquals('fileUploadErrorAttack', current($element->getErrors()));
        $this->assertEquals('fileUploadErrorAttack', current(array_keys($element->getMessages())));
        $this->assertNull($value);
    }
}