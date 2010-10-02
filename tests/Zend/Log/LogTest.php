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
 * @version    $Id: LogTest.php 22632 2010-07-18 18:30:08Z ramon $
 */

require_once dirname(__FILE__) . '/../../TestHelper.php';

/** Zend_Log */
require_once 'Zend/Log.php';

/** Zend_Log_Writer_Mock */
require_once 'Zend/Log/Writer/Mock.php';

/** Zend_Log_Writer_Stream */
require_once 'Zend/Log/Writer/Stream.php';

/** Zend_Log_FactoryInterface */
require_once 'Zend/Log/FactoryInterface.php';

/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Log
 */
class Zend_Log_LogTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->log = fopen('php://memory', 'w+');
        $this->writer = new Zend_Log_Writer_Stream($this->log);
    }

    // Writers

    public function testWriterCanBeAddedWithConstructor()
    {
        $logger = new Zend_Log($this->writer);
        $logger->log($message = 'message-to-log', Zend_Log::INFO);

        rewind($this->log);
        $this->assertContains($message, stream_get_contents($this->log));
    }

    public function testAddWriter()
    {
        $logger = new Zend_Log();
        $logger->addWriter($this->writer);
        $logger->log($message = 'message-to-log', Zend_Log::INFO);

        rewind($this->log);
        $this->assertContains($message, stream_get_contents($this->log));
    }

    public function testAddWriterAddsMultipleWriters()
    {
        $logger = new Zend_Log();

        // create writers for two separate streams of temporary memory
        $log1    = fopen('php://memory', 'w+');
        $writer1 = new Zend_Log_Writer_Stream($log1);
        $log2    = fopen('php://memory', 'w+');
        $writer2 = new Zend_Log_Writer_Stream($log2);

        // add the writers
        $logger->addWriter($writer1);
        $logger->addWriter($writer2);

        // log to both writers
        $logger->log($message = 'message-sent-to-both-logs', Zend_Log::INFO);

        // verify both writers were called by the logger
        rewind($log1);
        $this->assertContains($message, stream_get_contents($log1));
        rewind($log2);
        $this->assertContains($message, stream_get_contents($log2));

        // prove the two memory streams are different
        // and both writers were indeed called
        fwrite($log1, 'foo');
        $this->assertNotEquals(ftell($log1), ftell($log2));
    }

    public function testLoggerThrowsWhenNoWriters()
    {
        $logger = new Zend_Log();
        try {
            $logger->log('message', Zend_Log::INFO);
            $this->fail();
        } catch (Zend_Log_Exception $e) {
            $this->assertRegexp('/no writer/i', $e->getMessage());
        }
    }

    public function testDestructorCallsShutdownOnEachWriter()
    {
        $writer1 = new Zend_Log_Writer_Mock();
        $writer2 = new Zend_Log_Writer_Mock();

        $logger = new Zend_Log();
        $logger->addWriter($writer1);
        $logger->addWriter($writer2);

        $this->assertFalse($writer1->shutdown);
        $this->assertFalse($writer2->shutdown);

        $logger = null;

        $this->assertTrue($writer1->shutdown);
        $this->assertTrue($writer2->shutdown);
    }

    // Priorities

    public function testLogThrowsOnBadLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        try {
            $logger->log('foo', 42);
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/bad log priority/i', $e->getMessage());
        }
    }

    public function testLogThrough__callThrowsOnBadLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        try {
            $logger->nonexistantPriority('');
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/bad log priority/i', $e->getMessage());
        }
    }

    public function testAddingPriorityThrowsWhenOverridingBuiltinLogPriority()
    {
        try {
            $logger = new Zend_Log($this->writer);
            $logger->addPriority('BOB', 0);
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/existing priorities/i', $e->getMessage());
        }

    }

    public function testAddLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        $logger->addPriority('EIGHT', $priority = 8);

        $logger->eight($message = 'eight message');

        rewind($this->log);
        $logdata = stream_get_contents($this->log);
        $this->assertContains((string)$priority, $logdata);
        $this->assertContains($message, $logdata);
    }

    // Fields

    public function testLogWritesStandardFields() {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->info('foo');

        $this->assertEquals(1, count($mock->events));
        $event = array_shift($mock->events);

        $standardFields = array_flip(array('timestamp', 'priority', 'priorityName', 'message'));
        $this->assertEquals(array(), array_diff_key($event, $standardFields));
    }

    public function testLogWritesAndOverwritesExtraFields() {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->setEventItem('foo', 42);
        $logger->setEventItem($field = 'bar', $value = 43);
        $logger->info('foo');

        $this->assertEquals(1, count($mock->events));
        $event = array_shift($mock->events);

        $this->assertTrue(array_key_exists($field, $event));
        $this->assertEquals($value, $event[$field]);
    }

    /**
     * @group ZF-8491
     */
    public function testLogAcceptsExtrasParameterAsArrayAndPushesIntoEvent()
    {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->info('foo', array('content' => 'nonesuch'));
        $event = array_shift($mock->events);
        $this->assertContains('content', array_keys($event));
        $this->assertEquals('nonesuch', $event['content']);
    }

    /**
     * @group ZF-8491
     */
    public function testLogNumericKeysInExtrasArrayArePassedToInfoKeyOfEvent()
    {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->info('foo', array('content' => 'nonesuch', 'bar'));
        $event = array_shift($mock->events);
        $this->assertContains('content', array_keys($event));
        $this->assertContains('info', array_keys($event));
        $this->assertContains('bar', $event['info']);
    }

    /**
     * @group ZF-8491
     */
    public function testLogAcceptsExtrasParameterAsScalarAndAddsAsInfoKeyToEvent()
    {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->info('foo', 'nonesuch');
        $event = array_shift($mock->events);
        $this->assertContains('info', array_keys($event));
        $info = $event['info'];
        $this->assertContains('nonesuch', $info);
    }

    // Factory

    public function testLogConstructFromConfigStream()
    {
        $cfg = array('log' => array('memory' => array(
            'writerName'      => "Stream",
            'writerNamespace' => "Zend_Log_Writer",
            'writerParams'    => array(
                'stream'      => "php://memory"
            )
        )));

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }

    public function testLogConstructFromConfigStreamAndFilter()
    {
        $cfg = array('log' => array('memory' => array(
            'writerName'      => "Stream",
            'writerNamespace' => "Zend_Log_Writer",
            'writerParams'    => array(
                'stream'      => "php://memory"
            ),
            'filterName'   => "Priority",
            'filterParams' => array(
                'priority' => "Zend_Log::CRIT",
                'operator' => "<="
             ),
        )));

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }

    public function testFactoryUsesNameAndNamespaceWithoutModifications()
    {
        $cfg = array('log' => array('memory' => array(
            'writerName'      => "ZendMonitor",
            'writerNamespace' => "Zend_Log_Writer",
        )));

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }

	/**
     * @group ZF-9955
     */
    public function testExceptionConstructWriterFromConfig()
    {
        try {
            $logger = new Zend_Log();
            $writer = array('writerName' => 'NotExtendedWriterAbstract');
            $logger->addWriter($writer);
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('#^(Zend_Log_Writer_NotExtendedWriterAbstract|The\sspecified\swriter)#', $e->getMessage());
        }
    }

    /**
     * @group ZF-9956
     */
    public function testExceptionConstructFilterFromConfig()
    {
        try {
            $logger = new Zend_Log();
            $filter = array('filterName' => 'NotImplementsFilterInterface');
            $logger->addFilter($filter);
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('#^(Zend_Log_Filter_NotImplementsFilterInterface|The\sspecified\sfilter)#', $e->getMessage());
        }
    }

    /**
     * @group ZF-10170
     */
    public function testPriorityDuplicates()
    {
        $logger   = new Zend_Log();
        $mock     = new Zend_Log_Writer_Mock();
        $logger->addWriter($mock);
        try {
            $logger->addPriority('emerg', 8);
            $this->fail();
        } catch(Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertEquals('Existing priorities cannot be overwritten', $e->getMessage());
        }

        try {
            $logger->log('zf10170', 0);
            $logger->log('clone zf10170', 8);
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertEquals('Bad log priority', $e->getMessage());
        }
        $this->assertEquals(0, $mock->events[0]['priority']);
        $this->assertEquals('EMERG', $mock->events[0]['priorityName']);
        $this->assertFalse(array_key_exists(1, $mock->events));
    }
}

class Zend_Log_Writer_NotExtendedWriterAbstract implements Zend_Log_FactoryInterface
{
    public static function factory($config)
    {
    }
}

class Zend_Log_Filter_NotImplementsFilterInterface implements Zend_Log_FactoryInterface
{
    public static function factory($config)
    {
    }
}
