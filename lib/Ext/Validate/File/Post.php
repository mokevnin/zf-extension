<?php

class Ext_Validate_File_Post extends Ext_Validate_File_Post_Abstract
{
    public function _doValid($file, $content)
    {
        switch($content['error']) {
            case 0:
                break;
            case 1:
                $this->_throw($file, self::INI_SIZE);
                break;

            case 2:
                $this->_throw($file, self::FORM_SIZE);
                break;

            case 3:
                $this->_throw($file, self::PARTIAL);
                break;

            case 4:
                $this->_throw($file, self::NO_FILE);
                break;

            case 6:
                $this->_throw($file, self::NO_TMP_DIR);
                break;

            case 7:
                $this->_throw($file, self::CANT_WRITE);
                break;

            case 8:
                $this->_throw($file, self::EXTENSION);
                break;

            default:
                $this->_throw($file, self::UNKNOWN);
                break;
        }
    }
}