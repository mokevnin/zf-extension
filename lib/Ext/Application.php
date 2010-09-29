<?php

class Ext_Application extends Zend_Application
{
    private $_applicationPath;

    public function setOptions(array $options)
    {
        if (!empty($options['local_config'])) {
            $options = $this->mergeOptions($options, $this->_loadConfig($options['local_config']));
        }

        return parent::setOptions($options);
    }

    public function setApplicationPath($path)
    {
        $this->_applicationPath = rtrim($path, '/\\');

        return $this;
    }

    public function getApplicationPath()
    {
        return $this->_applicationPath;
    }
}