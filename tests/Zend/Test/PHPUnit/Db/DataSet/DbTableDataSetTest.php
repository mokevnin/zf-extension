<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Test
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DbTableDataSetTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

require_once dirname(__FILE__)."/../../../../../TestHelper.php";
require_once "Zend/Db/Table.php";
require_once "Zend/Test/PHPUnit/Db/DataSet/DbTableDataSet.php";

/**
 * @category   Zend
 * @package    Zend_Test
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Test
 */
class Zend_Test_PHPUnit_Db_DataSet_DbTableDataSetTest extends PHPUnit_Framework_TestCase
{
    public function testAddTableAppendedToTableNames()
    {
        $fixtureTable = "foo";

        $table = $this->getMock('Zend_Db_Table', array(), array(), '', false);
        $table->expects($this->at(0))->method('info')->with('name')->will($this->returnValue($fixtureTable));
        $table->expects($this->at(1))->method('info')->with('name')->will($this->returnValue($fixtureTable));
        $table->expects($this->at(2))->method('info')->with('cols')->will($this->returnValue(array()));

        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTableDataSet();
        $dataSet->addTable($table);

        $this->assertEquals(array($fixtureTable), $dataSet->getTableNames());
    }

    public function testAddTableCreatesDbTableInstance()
    {
        $fixtureTable = "foo";

        $table = $this->getMock('Zend_Db_Table', array(), array(), '', false);
        $table->expects($this->at(0))->method('info')->with('name')->will($this->returnValue($fixtureTable));
        $table->expects($this->at(1))->method('info')->with('name')->will($this->returnValue($fixtureTable));
        $table->expects($this->at(2))->method('info')->with('cols')->will($this->returnValue(array()));

        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTableDataSet();
        $dataSet->addTable($table);

        $this->assertType('Zend_Test_PHPUnit_Db_DataSet_DbTable', $dataSet->getTable($fixtureTable));
    }

    public function testGetUnknownTableThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTableDataSet();
        $dataSet->getTable('unknown');
    }
}
