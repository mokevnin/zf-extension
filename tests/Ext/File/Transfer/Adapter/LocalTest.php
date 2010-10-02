<?php

class Ext_File_Transfer_Adapter_LocalTest extends PHPUnit_Framework_TestCase
{
    protected $_adapter;
    protected $_transfer;
    protected $_text = 'example';

    public function setUp()
    {
        // << Prepare source file
        $tmpfname = tempnam(sys_get_temp_dir(), "ZFT");
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $this->_text);
        fclose($handle);
        // <<
        $tmpfname2 = tempnam(sys_get_temp_dir(), "ZFT");
        
        $_FILES = array(
            'file1' => array(
                'tmp_name' => $tmpfname,
                'name' => 'filename',
                'error' => 0,
            ),
            'files' => array(
                'tmp_name' => array($tmpfname, $tmpfname2),
                'name' => array(1, 2),
                'error' => array(0, 0)
            ),
        );

        $this->_adapter = new Ext_File_Transfer_Adapter_Local();
        $configurator = new Ext_File_Transfer_Adapter_Configurator_Local();
        $this->_adapter->setConfigurator($configurator);
        $this->_transfer = new Ext_File_Transfer();
        $this->_transfer->setAdapter($this->_adapter);
        
        Ext_Form_Element_File::setTransfer($this->_transfer);

        // >> set destination for all files
        $destination = sys_get_temp_dir() . '/example';
        @mkdir($destination, 0777);
        $this->_adapter->setDestination($destination);
        // <<
    }

    public function testSingleUpload()
    {
        $element = new Ext_Form_Element_File('file1');
        $element->addFilter(new Zend_Filter_File_UpperCase());

        $value = $element->getValue('file1');
        $this->assertTrue($value->isTransfered());
        $this->assertTrue(is_readable($this->_adapter->getFullFilePath()));
        $this->assertEquals(strtoupper($this->_text), file_get_contents($this->_adapter->getFullFilePath()));
    }

    public function testMultiUpload()
    {
        $element = new Ext_Form_Element_File('files');
        $element->setIsArray(true);

        $value = $element->getValue('files');

        foreach ($value as $file) {
            $this->assertTrue($file->isTransfered());
            $this->assertTrue(is_readable($this->_adapter->getFullFilePath()));
        }
    }

    public function testUploadError()
    {
        $element = new Ext_Form_Element_File('files');
        $element->setRequired(true);
        $this->assertNull($element->getValue('files'));

        $this->assertEquals('fileUploadErrorAttack', current($element->getErrors()));
        $this->assertEquals('fileUploadErrorAttack', current(array_keys($element->getMessages())));
    }
}