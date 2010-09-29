<?php

class Ext_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{
    /**
     *
     * @var Ext_Db_Table_Rowset
     */
    protected $_parentRowset;

    /**
     * Constructor.
     *
     * Supported params for $config are:-
     * - table       = class name or object of type Zend_Db_Table_Abstract
     * - data        = values of columns in this row.
     *
     * @param  array $config OPTIONAL Array of user-specified config options.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __construct(array $config = array())
    {
        if(isset($config['parentRowset'])) {
            $this->_parentRowset = $config['parentRowset'];
        }

        return parent::__construct($config);
    }

    public function deleteDependent($childrenTable, array $ids)
    {
        if(!sizeof($ids)) {
            return true;
        }

        if (!is_string($childrenTable)) {
            throw new Exception('Expected (string)');
        }

        $table = new $childrenTable;
        $reference = $table->getReference($this->getTableClass());

        $this_primary_key = current($this->_primary);
        $parent_primary_key = $reference['columns'];
        $children_primary_key = array_diff($table->info('primary'), $parent_primary_key);

        $parent_primary_key = current($parent_primary_key);
        $children_primary_key = current($children_primary_key);

        $adapter = $this->getTable()->getAdapter();

        $where = array();
        $where["\"$parent_primary_key\" = ?"] = $this->{$this_primary_key};

        $ids = join(', ', array_map('intval', $ids));

        $where[] = "\"$children_primary_key\" IN ($ids)";

        return $table->delete($where);
    }

    /**
     * Query a parent table to retrieve the single row matching the current row.
     *
     * @param string|Zend_Db_Table_Abstract $parentTable
     * @param string                        OPTIONAL $ruleKey
     * @param Ext_Db_Table_Select          OPTIONAL $select
     * @return Ext_Db_Table_Row   Query result from $parentTable
     * @throws Zend_Db_Table_Row_Exception If $parentTable is not a table or is not loadable.
     */
    public function findParentRow($parentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
    {
        if (!$this->_parentRowset instanceof Ext_Db_Table_Rowset) {
            return parent::findParentRow($parentTable, $ruleKey, $select);
        }

        $reference = $this->getTable()
            ->getReference($parentTable, $ruleKey);

        if (sizeof($reference['columns']) > 1) {
            throw new Zend_Db_Table_Row_Exception('Complex indexes are not supported'); // TODO
        }

        $refColumn = current($reference['columns']);

        return $this->_parentRowset->getDependentRow($parentTable, $this->$refColumn, $ruleKey, $select);
    }

    public function isModified()
    {
        foreach($this->_cleanData as $key => $value) {
            if((string) $this->_data[$key] !== (string) $value) {
                return true;
            }
        }
        return false;
    }
}
