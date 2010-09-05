<?php
/**
 * Работает с файлс
 */
class Ext_File_Transfer_HttpPost extends Ext_File_Transfer_Abstract
{
    public function __construct(Ext_File_Interface $fsAccess)
    {
        parent::__construct($fsAccess);

        $this->_prepareFiles();
        //$this->addValidator('Upload', false, $this->_files);
    }

    protected function _prepareFiles()
    {
        if (!isset($_FILES) || !sizeof($_FILES)) {
            return;
        }

        foreach ($_FILES as $form_name => $options) {
            if (!is_array($options['name'])) {
                $this->_files[$form_name] = new Ext_File_Transfer_File_HttpPost($form_name, $options);
            } else {
                $files = array();
                foreach ($options as $option_name => $values) {
                    foreach ($values as $index => $value) {
                        $new_form_name = $form_name . '_' . $index;
                        $files[$new_form_name][$option_name] = $value;
                    }
                }
                foreach ($files as $new_form_name => $options) {
                    $file = new Ext_File_Transfer_File_HttpPost($form_name, $options);
                    $this->_files[$new_form_name] = $file;
                    $this->_files[$form_name][$new_form_name] = $file;
                }
                
            }
        }
    }
}