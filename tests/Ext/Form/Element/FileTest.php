<?php

class Ext_Form_Element_FileTest extends PHPUnit_Framework_TestCase
{
    public function testGetAdapterException()
    {
        try {
            $element = new Ext_Form_Element_File('file1');
            $element->getTransfer();
            $this->fail('Expected Zend_Form_Element_Exception');
        } catch (Zend_Form_Element_Exception $e) {
            $this->assertTrue(true);
        }
    }
}