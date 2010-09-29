<?php

class Ext_View_Helper_ShortTest extends PHPUnit_Framework_TestCase
{
    private $_helper;

    public function setUp()
    {
        $this->_helper = new Ext_View_Helper_Short();
    }

    public function testDirect()
    {
        $text = $this->_helper->short(str_repeat('input', 5), 10);
        $this->assertEquals(10, mb_strlen($text));
    }
}