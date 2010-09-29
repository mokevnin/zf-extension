<?php

class Ext_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract
{
    protected $_dependentData;

    public function getFieldClasters($fieldName)
    {
        $list = array();
        foreach ($this as $row) {
            $list[$row->{$fieldName}][] = $row;
        }

        return $list;
    }

    public function getFieldSet($fieldName = null)
    {
        if (is_null($fieldName)) {
            $fieldName = $this->_getPrimary();
        }

        $list = array();
        foreach ($this->_data as $item) {
            if (!array_key_exists($fieldName, $item)) {
                trigger_error("'$fieldName' invalid field name");
                continue;
            }
            if (!empty($item[$fieldName])) {
                $list[] = $item[$fieldName];
            }
        }

        return array_unique($list);
    }

    public function getPairs($fieldName, $byFieldName = null)
    {
        if (is_null($byFieldName)) {
            $byFieldName = $this->_getPrimary();
        }

        $pairs = array();
        foreach ($this->_data as $item) {
            $pairs[$item[$byFieldName]] = $item[$fieldName];
        }

        return $pairs;
    }

    public function getRowByFieldValue($value, $fieldName = null)
    {
        if (is_null($fieldName)) {
            $fieldName = $this->_getPrimary();
        }
        $sort_rows = $this->getSortByFieldName($fieldName);

        return isset($sort_rows[$value]) ? $sort_rows[$value] : null;
    }

    public function getSortByPrimary()
    {
        return $this->getSortByFieldName($this->_getPrimary());
    }

    public function getSortByFieldName($fieldName, $toLowerCase = false)
    {
        $this->_checkField($fieldName);

        $items = array();
        foreach ($this as $row) {
            if ($toLowerCase) {
                $items[mb_strtolower($row->{$fieldName})] = $row;
            } else {
                $items[$row->{$fieldName}] = $row;
            }
        }

        return $items;
    }

    /**
     * @return Ext_Db_Table_Rowset
     */
    public function findParentRowset($parentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
    {
        if (!is_string($parentTable)) {
            throw new Exception('Expected (string)');
        }
        //TODO add Ext_Model_Manager
        $table = new $parentTable;
        $reference = $this->getTable()->getReference($parentTable, $ruleKey);
        $primary_ids = $this->getFieldSet(current($reference['columns']));
        if (sizeof($primary_ids)) {
            if ($select === null) {
                $select = $table->select()->reset();
            }

            $select->where(current($reference['refColumns']) . ' IN(?)', $primary_ids);
            $rowset = $table->fetchAll($select);
        } else {
            $data  = array(
                'table'    => $table,
                'data'     => array(),
                'rowClass' => $table->getRowClass(),
                'stored'   => true
            );
            $rowset_class_name = $table->getRowsetClass();

            $rowset = new $rowset_class_name($data);
        }

        return $rowset;
    }

    protected function _checkField($fieldName)
    {
        $cols = $this->getTable()->info(Zend_Db_Table_Abstract::COLS);

        if (!in_array($fieldName, $cols)) {
           throw new  Exception("Field '$fieldName' not found in " . $this->getTableClass());
        }
    }

    protected function _getPrimary()
    {
        $primaries = $this->getTable()->info('primary');

        return current($primaries);
    }

    /**
     * Return the current element.
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return Zend_Db_Table_Row_Abstract current element from the collection
     */
    public function current()
    {
        if ($this->valid() === false) {
            return null;
        }

        // do we already have a row object for this position?
        if (empty($this->_rows[$this->_pointer])) {
            $this->_rows[$this->_pointer] = new $this->_rowClass(
                array(
                    'table'    => $this->_table,
                    'data'     => $this->_data[$this->_pointer],
                    'stored'   => $this->_stored,
                    'readOnly' => $this->_readOnly,
                    'parentRowset' => $this
                )
            );
        }

        // return the row object
        return $this->_rows[$this->_pointer];
    }

    /**
     *
     * @param <type> $referenceName
     * @param <type> $key
     * @return Ext_Db_Table_Row
     */
    public function getDependentRow($referenceName, $key, $ruleKey = null, Zend_Db_Table_Select $select = null)
    {
        $reference = $this->getTable()
                ->getReference($referenceName, $ruleKey);
        $refColumn = current($reference['refColumns']);

        if (!isset($this->_dependentData[$referenceName])) {
            $this->_dependentData[$referenceName] = $this->findParentRowset($referenceName, $ruleKey, $select);
        }

        return $this->_dependentData[$referenceName]->getByValue($key, $refColumn);
    }

    public function getByValue($value, $fieldName = null)
    {
        if (is_null($fieldName)) {
            $fieldName = $this->_getPrimary();
        }

        if (!isset($this->_sortedData[$fieldName][$value])) {
            $this->_checkField($fieldName);

            $this->_sortedData[$fieldName] = array();
            foreach ($this->_data as $item) {
                $this->_sortedData[$fieldName][$item[$fieldName]] = $item;
            }
        }

        if (!isset($this->_sortedData[$fieldName][$value])) {
            return null;
        }

        if (!isset($this->_sortedRows[$fieldName][$value])) {
            $this->_sortedRows[$fieldName][$value] = new $this->_rowClass(
                array(
                    'table'    => $this->_table,
                    'data'     => $this->_sortedData[$fieldName][$value],
                    'stored'   => $this->_stored,
                    'readOnly' => $this->_readOnly,
                    'parentRowset' => $this
                )
            );
        }

        return $this->_sortedRows[$fieldName][$value];
    }

    public function getFieldsGroupBy($fieldName)
    {
        $list = array();
        foreach ($this as $row) {
            foreach ($row->toArray() as $key => $value) {
                $list[$row->$fieldName][$key] = $value;
            }
        }

        return $list;
    }
}
