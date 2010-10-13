<?php

class Ext_File_Adapter_LocalTest extends PHPUnit_Framework_TestCase
{
    protected $_adapter;
    protected $_transfer;
    protected $_text = 'example';
    protected $_destination;

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

        $this->_transfer = new Ext_File_Transfer();
        Ext_Form_Element_File::setTransfer($this->_transfer);

        // >> set destination for all files
        $this->_destination = sys_get_temp_dir() . '/example';
        @mkdir($this->_destination, 0777);
        $this->_adapter = $this->_transfer->getAdapter()->setDestination($this->_destination);
        // <<
    }

    public function testSingleUpload()
    {
        $element = new Ext_Form_Element_File('file1');
        $element->addFilter(new Zend_Filter_File_UpperCase());

        $element->setConfigurator(new Ext_File_Configurator_HttpPost());
        $value = $element->getValue('file1');
        $file_path = $this->_destination . '/' . $value;
        $this->assertTrue(is_readable($file_path));
        $this->assertEquals(strtoupper($this->_text), file_get_contents($file_path));
        $this->assertEquals($value, $_FILES['file1']['name']);
        
        $file = $this->_transfer->getFile('file1');
        $this->assertTrue($file->isTransfered());
    }

    public function testMultiUpload()
    {
        $element = new Ext_Form_Element_File('files');
        $element->setIsArray(true);

        $element->setConfigurator(new Ext_File_Configurator_HttpPost());
        $value = $element->getValue();
        foreach ($value as $file) {
            $file_path = $this->_destination . '/' . $file;
            $this->assertTrue(is_readable($file_path));
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