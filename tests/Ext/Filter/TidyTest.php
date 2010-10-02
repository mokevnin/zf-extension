<?php

class Ext_Filter_TidyTest extends PHPUnit_Framework_TestCase
{
    public $_filter;

    public function setUp()
    {
        $this->_filter = new Ext_Filter_Tidy();
    }

    public function testFilter()
    {
        $string = 'string';
        $result = $this->_filter->filter($string);
        $expected = "<p>\n  " . $string . "\n</p>";
        $this->assertEquals($expected, $result);
    }
}