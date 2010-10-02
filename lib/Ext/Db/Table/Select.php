<?php

class Ext_Db_Table_Select extends Zend_Db_Table_Select
{
    protected $_whereStack = array();
    
    /**
     *
     * @return Db_Table_Row
     */
    public function fetchRow()
    {
        return $this->getTable()->fetchRow($this);
    }

    /**
     *
     * @param <type> $limit
     * @return Ext_Db_Table_Rowset
     */
    public function fetchAll($limit = null, $offset = null)
    {
        if ($limit) {
            $this->limit($limit, $offset);
        }

        return $this->getTable()->fetchAll($this);
    }

    /**
     *
     * @param <type> $limit
     * @return Ext_Db_Table_Rowset
     */
    public function random($limit = 5)
    {
        $count = $this->count();
        $offset = ($count > $limit) ? $count - $limit : 0;

        $this->limit($limit, mt_rand(0, $offset));

        $rowset = $this->fetchAll();

        return $rowset;
    }

    /**
     *
     * @param <type> $page
     * @param <type> $limit
     * @return Zend_Paginator
     */
    public function getPaginator($page = 1, $limit = 10, $pageRange = 7)
    {
        $adaptee = new Ext_Paginator_AdapterAggregate($this);
        $paginator = Zend_Paginator::factory($adaptee);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange($pageRange);

        return $paginator;
    }

    public function count()
    {
        $select = clone $this;
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $query = sprintf('SELECT COUNT(*) AS "count" FROM (%s) AS "query"', $select->assemble());
        $stmt = $this->getAdapter()->query($query);

        return $stmt->fetchColumn();
    }

    public function max($field)
    {
        $select = $this->_prepareSelect();
        $select->columns("MAX($field) AS max");
        $row = $select->fetchRow();

        return $row->max;
    }

    public function min($field)
    {
        $select = $this->_prepareSelect();
        $select->columns("MIN($field) AS min");
        $row = $select->fetchRow();

        return $row->min;
    }

    public function sum($field)
    {
        $select = $this->_prepareSelect();
        $select->columns("SUM($field) AS sum");
        $row = $select->fetchRow();

        return (int) $row->sum;
    }

    private function _prepareSelect()
    {
        $select = clone $this;
        $select->reset(Zend_Db_Select::ORDER)
            ->reset(Zend_Db_Select::COLUMNS);

        if (!sizeof($select->getPart(Zend_Db_Table_Select::FROM))) {
            $select->from($select->getTable(), null);
        }

        return $select;
    }

    public function exists()
    {
        $row = $this->fetchRow();
        return!empty($row);
    }

    /**
     * Render UNION query
     *
     * @param string   $sql SQL query
     * @return string
     */
    protected function _renderUnion($sql)
    {
        if ($this->_parts[self::UNION]) {
            $parts = count($this->_parts[self::UNION]);
            foreach ($this->_parts[self::UNION] as $cnt => $union) {
                list($target, $type) = $union;
                if ($target instanceof Zend_Db_Select) {
                    $target = $target->assemble();
                }
                if ($cnt == 0) {
                    $sql .= '(';
                }
                $sql .= $target;
                if ($cnt < $parts - 1) {
                    $sql .= ') ' . $type . ' (';
                } else {
                    $sql .= ')';
                }
            }
        }

        return $sql;
    }

    /**
     *
     * @param <type> $fieldName
     * @param <type> $value
     * @return Ext_Db_Table_Select
     */
    public function by($fieldName, $value)
    {
        $field_name = $this->getAdapter()->quoteIdentifier($fieldName);
        return $this->where($field_name . ' IN (?)', $value);
    }

    /**
     *
     * @param array $data
     * @return Ext_Db_Table_Select
     */
    public function search(array $data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->getTable()->info(Zend_Db_Table_Abstract::COLS)))
                continue;
            $field_name = $this->getAdapter()->quoteIdentifier($key);
            $this->where($field_name . ' IN (?)', $value);
        }

        return $this;
    }

    /**
     *
     * @param <type> $field
     * @param <type> $more
     * @param <type> $less
     * @return Ext_Db_Table_Select
     */
    public function fieldBetween($field, $more, $less)
    {
        $more = $this->getAdapter()->quote($more);
        $less = $this->getAdapter()->quote($less);
        $field = $this->getAdapter()->quoteIdentifier($field);
        $expression = new Zend_Db_Expr(sprintf('%s BETWEEN %s AND %s', $field, $more, $less));
        return $this->where($expression);
    }

    /**
     *
     * @param <type> $value
     * @param <type> $fieldMore
     * @param <type> $fieldLess
     * @return Ext_Db_Table_Select
     */
    public function betweenFields($value, $fieldMore, $fieldLess)
    {
        $value = $this->getAdapter()->quote($value);
        $fieldMore = $this->getAdapter()->quoteIdentifier($fieldMore);
        $fieldLess = $this->getAdapter()->quoteIdentifier($fieldLess);
        $expression = new Zend_Db_Expr(sprintf('%s BETWEEN %s AND %s', $value, $fieldMore, $fieldLess));
        return $this->where($expression);
    }

    public function block()
    {
        array_push($this->_whereStack, 'AND ');
        array_push($this->_whereStack, '(');

        return $this;
    }

    public function orBlock()
    {
        array_push($this->_whereStack, 'OR ');
        array_push($this->_whereStack, '(');

        return $this;
    }

    public function endBlock()
    {
        array_push($this->_whereStack, ')');

        return $this;
    }

    protected function _registerCond($index)
    {
        return array_push($this->_whereStack, $index);
    }

    protected function _renderWhere($sql)
    {
        if ($this->_parts[self::FROM] && $this->_parts[self::WHERE]) {
            $parts = array();
            foreach ($this->_whereStack as $item) {
                if (!is_int($item)) {
                    $parts[] = $item;
                } else {
                    $parts[] = ' ' . $this->_parts[self::WHERE][$item] . ' ';
                }
            }
            $sql .= ' ' . self::SQL_WHERE . ' ' .  implode('', $parts);
        }

        return $sql;
    }

    public function where($cond, $value = null, $type = null)
    {
        $this->_parts[self::WHERE][] = $this->_where($cond, $value, $type, true);
        $this->_registerCond(sizeof($this->_parts[self::WHERE]) - 1);

        return $this;
    }

    public function orWhere($cond, $value = null, $type = null)
    {
        $this->_parts[self::WHERE][] = $this->_where($cond, $value, $type, false);
        $this->_registerCond(sizeof($this->_parts[self::WHERE]) - 1);

        return $this;
    }

    protected function _where($condition, $value = null, $type = null, $bool = true)
    {
        if (count($this->_parts[self::UNION])) {
            require_once 'Zend/Db/Select/Exception.php';
            throw new Zend_Db_Select_Exception("Invalid use of where clause with " . self::SQL_UNION);
        }

        if ($value !== null) {
            $condition = $this->_adapter->quoteInto($condition, $value, $type);
        }

        if ($this->_parts[self::WHERE] and is_int($this->_whereStack[sizeof($this->_whereStack) - 1])) {
            if ($bool === true) {
                $this->_registerCond('AND');
            } else {
                $this->_registerCond('OR');
            }
        }

        return "($condition)";
    }
}