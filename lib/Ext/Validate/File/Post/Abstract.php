<?php

abstract class Ext_Validate_File_Post_Abstract extends Zend_Validate_File_Upload
{
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the file was uploaded without errors
     *
     * @param  string $value Single file to check for upload errors, when giving null the $_FILES array
     *                       from initialization will be used
     * @return boolean
     */
    public function isValid($value, $file = null)
    {
        if (array_key_exists($value, $this->_files)) {
            $files[$value] = $this->_files[$value];
        } else {
            foreach ($this->_files as $file => $content) {
                if (isset($content['name']) && ($content['name'] === $value)) {
                    $files[$file] = $this->_files[$file];
                }

                if (isset($content['tmp_name']) && ($content['tmp_name'] === $value)) {
                    $files[$file] = $this->_files[$file];
                }
            }
        }

        if (empty($files)) {
            return $this->_throw($file, self::FILE_NOT_FOUND);
        }

        foreach ($files as $file => $content) {
            $this->_value = $file;
            $this->_doValid($file, $content);
        }

        if (count($this->_messages) > 0) {
            return false;
        } else {
            return true;
        }
    }

    abstract protected function _doValid($file, $error);
}