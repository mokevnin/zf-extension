<?php

class Ext_View_Helper_Breadcrumbs extends Zend_View_Helper_Abstract
{
    private $_items = array();
    private $_separator = ' / ';
    
    public function breadcrumbs($title = null, $route = null, array $params = array())
    {
        if (is_null($title)) {
            return $this;
        }
        $item['title']  = $title;
        $item['router'] = $route;
        $item['params'] = $params;
     
        $this->_items[] = $item;

        return $this;
    }

    public function render()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $items = array();
        foreach ($this->_items as $item) {
            if ($item['router'] == $router->getCurrentRouteName()) {
                $items[] = $item['title'];
            } else {
                $url = $router->assemble($item['params'], $item['router']);
                $items[] = sprintf('<a href="%s">%s</a>', $url, $item['title']);
            }
        }
        return implode($this->getSeparator(), $items);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function setSeparator($separator)
    {
        $this->_separator = $separator;
    }

    public function getSeparator()
    {
        return $this->_separator;
    }
}