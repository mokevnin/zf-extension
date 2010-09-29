<?php

class Ext_View_Helper_Inner extends Zend_View_Helper_Abstract
{
    private $_doIt = false;

    public function Inner()
    {
        return $this;
    }

    public function check()
    {
        if ($this->_doIt) {
            return true;
        }
        $this->_doIt = true;

        return false;
    }

    public function reset()
    {
        $this->_doIt = false;

        return $this;
    }
}