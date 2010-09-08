<?php

class Ext_File_Transfer_Adapter_LocalTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_FILES = array(
            'formname' => array(
                'tmp_name' => tempnam('/tmp', 'tmp_name'),
                'name' => 'filename',
                'error' => 0,
            )
        );

        $this->_adapter = new Ext_File_Transfer_Adapter_Local();
        $this->_transfer = new Ext_File_Transfer($this->_adapter);
        Ext_Form_Element_File::setTransfer($this->_transfer);
    }

    public function testUsage()
    {
        $new_file_path = '/tmp/newfilename';

        $element = new Ext_Form_Element_File('formname');
        $adapter = $element->getAdapter();
        $adapter->setDestination($new_file_path);
        $value = $form->getValue('formname');

        $this->assertEquals($new_file_path, (string) $value);
    }
}