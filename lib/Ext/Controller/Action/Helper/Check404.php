<?php

class Ext_Controller_Action_Helper_Check404 extends Zend_Controller_Action_Helper_Abstract
{
    public function direct($value, $message = 'Page not found')
    {
        $throw = false;
        if (is_array($value)) {
            $throw = sizeof($value) ? false : true;
        } elseif(is_bool($value)) {
            $throw = !$value;
        } else {
            $throw = !empty($value) ? false : true;
        }

        if ($throw) {
            throw new Zend_Controller_Action_Exception($message, 404);
        }

        return true;
    }
}