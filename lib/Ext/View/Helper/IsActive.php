<?php

/**
 * @category   Ext
 * @package    Ext_View
 * @subpackage Helper
 * @author     Mokevnin Kirill <mokevnin@gmail.com>
 * @license    New BSD License
 */

/**
 * Helper for making active link
 */

class Ext_View_Helper_IsActive extends Zend_View_Helper_Abstract
{
    public function isActive()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $module = $request->getParam('module');
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        foreach (func_get_args() as $place) {
            $current_place = $module;
            if (2 == substr_count($place, ':')) {
                $current_place .= ':' . $controller . ':' . $action;
            } else if (1 == substr_count($place, ':')) {
                $current_place .= ':' . $controller;
            }

            if ($current_place == $place) {
                return true;
            }
        }

        return false;
    }
}