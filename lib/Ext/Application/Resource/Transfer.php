<?php

/**
 * @category   Ext
 * @package    Ext_Application
 * @subpackage Resource
 * @author     Mokevnin Kirill <mokevnin@gmail.com>
 * @license    New BSD License
 */

class Ext_Application_Resource_Transfer
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Ext_File_Transfer
     */
    protected $_transfer;

    public function init()
    {
        return $this->getTransfer();
    }

    public function getTransfer()
    {
        if ($this->_transfer) {
            return $this->_transfer;
        }

        $options = $this->getOptions();

        // adapter
        $adapter_class = 'Ext_File_Transfer_Adapter_Local';
        $params = array();
        if (!empty($options['adapter']['name'])) {
            $adapter_class = $options['adapter']['name'];
        }
        if (isset($options['adapter']['params']) && is_array($options['adapter']['params'])) {
            $params = $options['adapter']['params'];
        }
        $adapter = new $adapter_class($params);

        // transfer
        $transfer = new Ext_File_Transfer();
        $transfer->setAdapter($adapter);
        
        Ext_Form_Element_File::setTransfer($transfer);

        return $transfer;
    }
}