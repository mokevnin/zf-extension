<?php

abstract class Ext_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * @var Ext_Db_Table_Row
     */
    protected $_rowClass = 'Ext_Db_Table_Row';

    /**
     * @var Ext_Db_Table_RowSet
     */
    protected $_rowsetClass = 'Ext_Db_Table_Rowset';

    public function  __toString()
    {
        return $this->_name;
    }

    public function update(array $data, $where)
    {
        $info = $this->info('cols');
        $data = array_intersect_key($data, array_flip($info));

        return parent::update($data, $where);
    }

    public function exists()
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            if ($arg === null) {
                return false;
            }
        }

        $rowset = call_user_func_array(array('parent', 'find'), $args);
        $row = $rowset->current();

        return !empty($row);
    }

    /**
     * @return Ext_Db_Table_Select
     */
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        $select = new Ext_Db_Table_Select($this);
        if ($withFromPart == self::SELECT_WITH_FROM_PART) {
            $select->from($this->info(self::NAME), Zend_Db_Table_Select::SQL_WILDCARD, $this->info(self::SCHEMA));
        }

        return $select;
    }
}