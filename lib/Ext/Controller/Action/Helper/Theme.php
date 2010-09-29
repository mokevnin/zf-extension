<?php

class Ext_Controller_Action_Helper_Theme extends Zend_Controller_Action_Helper_Abstract
{
    private $_theme;
    private $_disabled = false;

    public function preDispatch()
    {
        if ($this->_disabled) {
            return;
        }

        $paths = $this->getActionController()->view->getScriptPaths();
        $paths[] = $this->getFrontController()->getModuleDirectory() . '/views/' . $this->_theme;

        $this->getActionController()->view->setScriptPath($paths);
    }

    public function setTheme($theme)
    {
        $this->_theme = $theme;
    }

    public function direct()
    {
        return $this;
    }

    public function setDisabled($disabled = true)
    {
        $this->_disabled = $disabled;
    }
}