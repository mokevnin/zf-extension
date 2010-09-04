<?php

/**
 * @category   Ext
 * @package    Ext_Application
 * @subpackage Resource
 * @author     Mokevnin Kirill <mokevnin@gmail.com>
 * @license    New BSD License
 */

/**
 * @see http://dklab.ru/lib/PHP_Exceptionizer/
 */
class Ext_Application_Resource_Exceptionizer
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var PHP_Exceptionizer
     */
    protected $_exceptionizer;

    public function init()
    {
        return $this->getExceptionizer();
    }

    public function getExceptionizer()
    {
        if ($this->_exceptionizer) {
            return $this->_exceptionizer;
        }

        $options = $this->getOptions();
        $mask = isset($options['mask']) ? $options['mask'] : E_ALL;
        $ignore_other = isset($options['ignore_other']) ? $options['ignore_other'] : false;

        return new PHP_Exceptionizer($mask, $ignore_other);
    }
}