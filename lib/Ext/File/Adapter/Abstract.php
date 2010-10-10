<?php

abstract class Ext_File_Adapter_Abstract implements Ext_File_Adapter_Interface
{
    public function  __construct(array $params = array())
    {
        foreach ($params as $key => $value) {
            $method = 'set' . ucfirst(mb_strtolower($key));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}