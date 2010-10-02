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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: StaticTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__);

/**
 * @see Zend_Db
 */
require_once 'Zend/Db.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * @see Zend_Db_Adapter_Static
 */
require_once 'Zend/Db/Adapter/Static.php';


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Db
 * @group      Zend_Db_Adapter
 */
class Zend_Db_Adapter_StaticTest extends PHPUnit_Framework_TestCase
{

    protected static $_isCaseSensitiveFileSystem = null;

    public function testDbConstructor()
    {
        $db = new Zend_Db_Adapter_Static( array('dbname' => 'dummy') );
        $this->assertType('Zend_Db_Adapter_Abstract', $db);
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbConstructorExceptionInvalidOptions()
    {
        list($major, $minor, $revision) = explode('.', PHP_VERSION);
        if ($minor >= 2) {
            try {
                $db = new Zend_Db_Adapter_Static('scalar');
                $this->fail('Expected exception not thrown');
            } catch (Exception $e) {
                $this->assertContains('Adapter parameters must be in an array or a Zend_Config object', $e->getMessage());
            }
        } else {
            $this->markTestIncomplete('Failure to meet type hint results in fatal error in PHP < 5.2.0');
        }
    }

    public function testDbConstructorZendConfig()
    {
        $configData1 = array(
            'adapter' => 'Static',
            'params' => array(
                'dbname' => 'dummy'
            )
        );
        $config1 = new Zend_Config($configData1);
        $db = new Zend_Db_Adapter_Static($config1->params);
        $this->assertType('Zend_Db_Adapter_Abstract', $db);
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbFactory()
    {
        $db = Zend_Db::factory('Static', array('dbname' => 'dummy') );
        $this->assertType('Zend_Db_Adapter_Abstract', $db);
        $this->assertTrue(class_exists('Zend_Db_Adapter_Static'));
        $this->assertType('Zend_Db_Adapter_Static', $db);
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbFactoryAlternateNamespace()
    {
        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        try {
            // this test used to read as 'TestNamespace', but due to ZF-5606 has been changed
            $db = Zend_Db::factory('Static', array('dbname' => 'dummy', 'adapterNamespace' => 'Testnamespace'));
        } catch (Zend_Exception $e) {
            set_include_path($ip);
            $this->fail('Caught exception of type '.get_class($e).' where none was expected: '.$e->getMessage());
        }

        set_include_path($ip);

        $this->assertType('Zend_Db_Adapter_Abstract', $db);
        $this->assertTrue(class_exists('Zend_Db_Adapter_Static'));
        $this->assertType('Zend_Db_Adapter_Static', $db);
        $this->assertTrue(class_exists('TestNamespace_Static'));
        $this->assertType('TestNamespace_Static', $db);
    }

    public function testDbFactoryAlternateNamespaceExceptionInvalidAdapter()
    {
        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        try {
            $db = Zend_Db::factory('Version', array('dbname' => 'dummy', 'adapterNamespace' => 'Zend'));
            set_include_path($ip);
            $this->fail('Expected to catch Zend_Db_Exception');
        } catch (Zend_Exception $e) {
            set_include_path($ip);
            $this->assertType('Zend_Db_Exception', $e,
                'Expected exception of type Zend_Db_Exception, got '.get_class($e));
            $this->assertEquals("Adapter class 'Zend_Version' does not extend Zend_Db_Adapter_Abstract", $e->getMessage());
        }
    }

    public function testDbFactoryExceptionInvalidDriverName()
    {
        try {
            $db = Zend_Db::factory(null);
            $this->fail('Expected to catch Zend_Db_Exception');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Exception', $e,
                'Expected exception of type Zend_Db_Exception, got '.get_class($e));
            $this->assertEquals($e->getMessage(), 'Adapter name must be specified in a string');
        }
    }

    public function testDbFactoryExceptionInvalidOptions()
    {
        list($major, $minor, $revision) = explode('.', PHP_VERSION);
        if ($minor >= 2) {
            try {
                $db = Zend_Db::factory('Static', 'scalar');
                $this->fail('Expected exception not thrown');
            } catch (Exception $e) {
                $this->assertContains('Adapter parameters must be in an array or a Zend_Config object', $e->getMessage());
            }
        } else {
            $this->markTestIncomplete('Failure to meet type hint results in fatal error in PHP < 5.2.0');
        }
    }

    public function testDbFactoryExceptionNoConfig()
    {
        list($major, $minor, $revision) = explode('.', PHP_VERSION);
        if ($minor >= 2) {
            try {
                $db = Zend_Db::factory('Static');
                $this->fail('Expected exception not thrown');
            } catch (Exception $e) {
                $this->assertContains('Configuration must have a key for \'dbname\' that names the database instance', $e->getMessage());
            }
        } else {
            $this->markTestIncomplete('Failure to meet type hint results in fatal error in PHP < 5.2.0');
        }
    }

    public function testDbFactoryExceptionNoDatabaseName()
    {
        try {
            $db = Zend_Db::factory('Static', array());
            $this->fail('Expected to catch Zend_Db_Adapter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Adapter_Exception', $e,
                'Expected exception of type Zend_Db_Adapter_Exception, got '.get_class($e));
            $this->assertEquals("Configuration must have a key for 'dbname' that names the database instance", $e->getMessage());
        }
    }

    public function testDbFactoryZendConfig()
    {
        $configData1 = array(
            'adapter' => 'Static',
            'params' => array(
                'dbname' => 'dummy'
            )
        );
        $config1 = new Zend_Config($configData1);
        $db = Zend_Db::factory($config1);
        $this->assertType('Zend_Db_Adapter_Static', $db);
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbFactoryZendConfigExceptionNoAdapter()
    {
        $configData1 = array(
            'params' => array(
                'dbname' => 'dummy'
            )
        );
        $config1 = new Zend_Config($configData1);
        try {
            $db = Zend_Db::factory($config1);
            $this->fail('Expected to catch Zend_Db_Exception');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Exception', $e,
                'Expected exception of type Zend_Db_Exception, got '.get_class($e));
            $this->assertEquals($e->getMessage(), 'Adapter name must be specified in a string');
        }
    }

    public function testDbFactoryZendConfigOverrideArray()
    {
        $configData1 = array(
            'adapter' => 'Static',
            'params' => array(
                'dbname' => 'dummy'
            )
        );
        $configData2 = array(
            'dbname' => 'vanilla'
        );
        $config1 = new Zend_Config($configData1);
        $db = Zend_Db::factory($config1, $configData2);
        $this->assertType('Zend_Db_Adapter_Static', $db);
        // second arg should be ignored
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbFactoryZendConfigOverrideZendConfig()
    {
        $configData1 = array(
            'adapter' => 'Static',
            'params' => array(
                'dbname' => 'dummy'
            )
        );
        $configData2 = array(
            'dbname' => 'vanilla'
        );
        $config1 = new Zend_Config($configData1);
        $config2 = new Zend_Config($configData2);
        $db = Zend_Db::factory($config1, $config2);
        $this->assertType('Zend_Db_Adapter_Static', $db);
        // second arg should be ignored
        $this->assertEquals('dummy', $db->config['dbname']);
    }

    public function testDbGetConnection()
    {
        $db = Zend_Db::factory('Static', array('dbname' => 'dummy'));
        $conn = $db->getConnection();
        $this->assertType('Zend_Db_Adapter_Static', $conn);
    }

    public function testDbGetFetchMode()
    {
        $db = Zend_Db::factory('Static', array('dbname' => 'dummy'));
        $mode = $db->getFetchMode();
        $this->assertType('integer', $mode);
    }

    /**
     * @group ZF-5099
     */
    public function testDbGetServerVersion()
    {
        $db = Zend_Db::factory('Static', array('dbname' => 'dummy'));
        $version = $db->getServerVersion();
        $this->assertEquals($version, '5.6.7.8');
        $this->assertTrue(version_compare($version, '1.0.0', '>'));
        $this->assertTrue(version_compare($version, '99.0.0', '<'));
    }

    /**
     * @group ZF-5050
     */
    public function testDbCloseConnection()
    {
        $db = Zend_Db::factory('Static', array('dbname' => 'dummy'));
        $db->getConnection();
        $this->assertTrue($db->isConnected());
        $db->closeConnection();
        $this->assertFalse($db->isConnected());
    }

    /**
     * @group ZF-5606
     */
    public function testDbFactoryDoesNotNormalizeNamespace()
    {
        $newIncludePath = realpath(dirname(__FILE__) . '/_files/') . PATH_SEPARATOR . get_include_path();
        $oldIncludePath = set_include_path($newIncludePath);

        try {
            $adapter = Zend_Db::factory(
                'Dbadapter',
                array('dbname' => 'dummy', 'adapterNamespace' => 'Test_MyCompany1')
                );
        } catch (Exception $e) {
            set_include_path($oldIncludePath);
            $this->fail('Could not load file for reason: ' . $e->getMessage());
        }
        $this->assertEquals('Test_MyCompany1_Dbadapter', get_class($adapter));
        set_include_path($oldIncludePath);

    }

    /**
     * @group ZF-5606
     */
    public function testDbFactoryWillThrowExceptionWhenAssumingBadBehavior()
    {
        $newIncludePath = realpath(dirname(__FILE__) . '/_files/') . PATH_SEPARATOR . get_include_path();
        $oldIncludePath = set_include_path($newIncludePath);

        if (!$this->_isCaseSensitiveFileSystem()) {
            set_include_path($oldIncludePath);
            $this->markTestSkipped('This test is irrelevant on case-inspecific file systems.');
            return;
        }

        try {
            $adapter = Zend_Db::factory(
                'Dbadapter',
                array('dbname' => 'dummy', 'adapterNamespace' => 'Test_MyCompany2')
                );
        } catch (Exception $e) {
            set_include_path($oldIncludePath);
            $this->assertContains('failed to open stream', $e->getMessage());
            return;
        }

        $this->assertFalse($adapter instanceof Test_Mycompany2_Dbadapter);
        set_include_path($oldIncludePath);
    }

    /**
     * @group ZF-7924
     */
    public function testDbFactoryWillLoadCaseInsensitiveAdapterName()
    {
        $newIncludePath = realpath(dirname(__FILE__) . '/_files/') . PATH_SEPARATOR . get_include_path();
        $oldIncludePath = set_include_path($newIncludePath);

        try {
            $adapter = Zend_Db::factory(
                'DB_ADAPTER',
                array('dbname' => 'dummy', 'adapterNamespace' => 'Test_MyCompany1')
                );
        } catch (Exception $e) {
            set_include_path($oldIncludePath);
            $this->fail('Could not load file for reason: ' . $e->getMessage());
        }
        $this->assertEquals('Test_MyCompany1_Db_Adapter', get_class($adapter));
        set_include_path($oldIncludePath);

    }

    protected function _isCaseSensitiveFileSystem()
    {
        if (self::$_isCaseSensitiveFileSystem === null) {
            self::$_isCaseSensitiveFileSystem = !(@include 'Test/MyCompany1/iscasespecific.php');
        }

        return self::$_isCaseSensitiveFileSystem;
    }

    public function getDriver()
    {
        return 'Static';
    }

}
