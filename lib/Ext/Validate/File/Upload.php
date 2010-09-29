<?php

class Ext_Validate_File_Upload extends Ext_Validate_File_Post_Abstract
{
    protected function _doValid($file, $content)
    {
        switch($content['error']) {
            case 0:
                if (!is_uploaded_file($content['tmp_name'])) {
                    $this->_throw($file, self::ATTACK);
                }
                break;
        }
    }
}