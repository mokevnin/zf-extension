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
 * @package    Zend_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: SuppressTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/** PHPUnit_Framework_TestCase */
require_once 'PHPUnit/Framework/TestCase.php';

/** Zend_Log */
require_once 'Zend/Log.php';

/** Zend_Log_Filter_Suppress */
require_once 'Zend/Log/Filter/Suppress.php';

/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Log
 */
class Zend_Log_Filter_SuppressTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->filter = new Zend_Log_Filter_Suppress();
    }

    public function testSuppressIsInitiallyOff()
    {
        $this->assertTrue($this->filter->accept(array()));
    }

    public function testSuppressOn()
    {
        $this->filter->suppress(true);
        $this->assertFalse($this->filter->accept(array()));
        $this->assertFalse($this->filter->accept(array()));
    }

    public function testSuppressOff()
    {
        $this->filter->suppress(false);
        $this->assertTrue($this->filter->accept(array()));
        $this->assertTrue($this->filter->accept(array()));
    }

    public function testSuppressCanBeReset()
    {
        $this->filter->suppress(true);
        $this->assertFalse($this->filter->accept(array()));
        $this->filter->suppress(false);
        $this->assertTrue($this->filter->accept(array()));
        $this->filter->suppress(true);
        $this->assertFalse($this->filter->accept(array()));
    }
    
    public function testFactory()
    {
        $cfg = array('log' => array('memory' => array(
            'writerName' => "Mock", 
            'filterName' => "Suppress"
        )));

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }
}
