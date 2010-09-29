<?php

class Ext_View_Helper_Qf extends Zend_View_Helper_Abstract
{
    private $_params = array();
    private $_prefix = '';

    /**
     * QueryFilter
     * @param array $params
     * @return Ext_View_Helper_Qf
     */
    public function qf(array $params = null)
    {
        if (null != $params) {
            $this->_params = $params;
        }

        return $this;
    }

    public function setPrefix($prefix = '')
    {
        $this->_prefix = $prefix;
        return $this;
    }

    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * AddParam
     * @param string $fieldName
     * @param string $value
     * @return View_Helper_Qf
     */
    public function ap($fieldName, $value)
    {
        $params = $this->_params;
        $params[$fieldName] = $value;

        return $this;
    }

    /**
     * GetParam
     * @param string $fieldName
     * @return string
     */
    public function gp($fieldName)
    {
        return isset($this->_params[$fieldName]) ? $this->_params[$fieldName] : null;
    }

    /**
     * AddParams
     * @param array $params
     * @return Ext_View_Helper_Qf
     */
    public function aps(array $params)
    {
        $old_params = $this->_params;
        foreach ($params as $key => $value) {
            if (empty($value)) {
                unset($old_params[$key]);
            } else {
                $old_params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * RemoveParam
     * @param string $fieldName
     * @return Ext_View_Helper_Qf
     */
    public function rp($fieldName)
    {
        $params = $this->_params;
        unset($params[$fieldName]);

        return $this;
    }

    /**
     * RemoveParams
     * @param array $fieldNames
     * @return Ext_View_Helper_Qf
     */
    public function rps(array $fieldNames)
    {
        $params = $this->_params;
        foreach ($fieldNames as $field_name) {
            unset($params[$field_name]);
        }

        return $this;
    }

    /**
     * ArrayToString
     * @param array $params
     * @return string
     */
    public function ats(array $params)
    {
        $pairs = array();
        foreach ($params as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }

        if(sizeof($pairs)) {
            return $this->getPrefix() . implode('&', $pairs);
        }
        return "";
    }

    public function __toString()
    {
        return $this->ats($this->_params);
    }
}