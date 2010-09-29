<?php

class Ext_Db_Adapter_Pdo_Pgsql extends Zend_Db_Adapter_Pdo_Pgsql
{
    protected $_count;

    public function beginTransaction()
    {
        $this->_count++;

        if ($this->_count < 2) {
            return parent::beginTransaction();
        }

        return false;
    }

    public function commit()
    {
        $this->_count--;
        if ($this->_count < 1) {
            return parent::commit();
        }

        return false;
    }

    public function rollBack()
    {
        $this->_count--;
        if ($this->_count < 1) {
            return parent::rollBack();
        }

        return false;
    }

    public function isTransactionStarted()
    {
        return (bool)$this->_count;
    }

    public function resetCounter()
    {
        $this->_count = 0;
    }
}