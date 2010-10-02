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
 * @package    Zend_Service_WindowsAzure
 * @subpackage UnitTests
 * @version    $Id$
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Test helpers
 */
require_once dirname(__FILE__) . '/../../../../TestHelper.php';

/** Zend_Service_WindowsAzure_Storage_Blob */
require_once 'Zend/Service/WindowsAzure/Storage/Blob.php';

/** Zend_Service_WindowsAzure_Diagnostics_Manager */
require_once 'Zend/Service/WindowsAzure/Diagnostics/Manager.php';

/** Zend_Service_WindowsAzure_Diagnostics_ConfigurationInstance */
require_once 'Zend/Service/WindowsAzure/Diagnostics/ConfigurationInstance.php';

/**
 * @category   Zend
 * @package    Zend_Service_WindowsAzure
 * @subpackage UnitTests
 * @version    $Id$
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_WindowsAzure_Diagnostics_ManagerTest extends PHPUnit_Framework_TestCase
{

    protected static $uniqId = 0;
    
    protected function setup()
    {
        if (!TESTS_ZEND_SERVICE_WINDOWSAZURE_DIAGNOSTICS_RUNTESTS) {
            $this->markTestSkipped('Skipping Diagnostic tests, to enable set TESTS_ZEND_SERVICE_WINDOWSAZURE_DIAGNOSTICS_RUNTESTS to true in TestConfiguration.php');
        }
    }
    
    /**
     * Test teardown
     */
    protected function tearDown()
    {
        if ($this->status == PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED) {
            return;
        }
        
        $storageClient = $this->createStorageInstance();
        for ($i = 1; $i <= self::$uniqId; $i++)
        {
            try { $storageClient->deleteContainer(TESTS_ZEND_SERVICE_WINDOWSAZURE_DIAGNOSTICS_CONTAINER_PREFIX . $i); } catch (Exception $e) { }
        }
    }

    protected function createStorageInstance()
    {
        $storageClient = null;
        if (TESTS_ZEND_SERVICE_WINDOWSAZURE_DIAGNOSTICS_RUNONPROD)
        {
            $storageClient = new Zend_Service_WindowsAzure_Storage_Blob(TESTS_ZEND_SERVICE_WINDOWSAZURE_BLOB_HOST_PROD, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_ACCOUNT_PROD, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_KEY_PROD, false, Zend_Service_WindowsAzure_RetryPolicy_RetryPolicyAbstract::retryN(10, 250));
        } else {
            $storageClient = new Zend_Service_WindowsAzure_Storage_Blob(TESTS_ZEND_SERVICE_WINDOWSAZURE_BLOB_HOST_DEV, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_ACCOUNT_DEV, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_KEY_DEV, true, Zend_Service_WindowsAzure_RetryPolicy_RetryPolicyAbstract::retryN(10, 250));
        }
        
        if (TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_USEPROXY) {
            $storageClient->setProxy(TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_USEPROXY, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_PROXY, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_PROXY_PORT, TESTS_ZEND_SERVICE_WINDOWSAZURE_STORAGE_PROXY_CREDENTIALS);
        }

        return $storageClient;
    }
    
    protected function generateName()
    {
        self::$uniqId++;
        return TESTS_ZEND_SERVICE_WINDOWSAZURE_DIAGNOSTICS_CONTAINER_PREFIX . self::$uniqId;
    }
    
    /**
     * Test manager initialize
     */
    public function testManagerInitialize()
    {
		$controlContainer = $this->generateName();
		
		$storageClient = $this->createStorageInstance();
        $manager = new Zend_Service_WindowsAzure_Diagnostics_Manager($storageClient, $controlContainer);
        
        $result = $storageClient->containerExists($controlContainer);
        $this->assertTrue($result);
    }
    
	/**
     * Test manager default configuration
     */
    public function testManagerDefaultConfiguration()
    {
		$controlContainer = $this->generateName();
		
		$storageClient = $this->createStorageInstance();
        $manager = new Zend_Service_WindowsAzure_Diagnostics_Manager($storageClient, $controlContainer);
        
        $configuration = $manager->getDefaultConfiguration();
        $manager->setConfigurationForRoleInstance('test', $configuration);
        
        $this->assertEquals($configuration->toXml(), $manager->getConfigurationForRoleInstance('test')->toXml());
    }
    
	/**
     * Test manager custom configuration
     */
    public function testManagerCustomConfiguration()
    {
		$controlContainer = $this->generateName();
		
		$storageClient = $this->createStorageInstance();
        $manager = new Zend_Service_WindowsAzure_Diagnostics_Manager($storageClient, $controlContainer);
        
        $configuration = $manager->getDefaultConfiguration();
		$configuration->DataSources->OverallQuotaInMB = 1;
		$configuration->DataSources->Logs->BufferQuotaInMB = 1;
		$configuration->DataSources->Logs->ScheduledTransferPeriodInMinutes = 1;
		$configuration->DataSources->PerformanceCounters->BufferQuotaInMB = 1;
		$configuration->DataSources->PerformanceCounters->ScheduledTransferPeriodInMinutes = 1;
		$configuration->DataSources->DiagnosticInfrastructureLogs->BufferQuotaInMB = 1;
		$configuration->DataSources->DiagnosticInfrastructureLogs->ScheduledTransferPeriodInMinutes = 1;
		$configuration->DataSources->PerformanceCounters->addSubscription('\Processor(*)\% Processor Time', 1);
		$configuration->DataSources->WindowsEventLog->addSubscription('System!*');
		$configuration->DataSources->WindowsEventLog->addSubscription('Application!*');
        $manager->setConfigurationForRoleInstance('test', $configuration);
        
        $result = $manager->getConfigurationForRoleInstance('test');
        
        $this->assertEquals($configuration->toXml(), $result->toXml());
        $this->assertEquals(1, count($result->DataSources->PerformanceCounters->Subscriptions));
        $this->assertEquals(2, count($result->DataSources->WindowsEventLog->Subscriptions));
    }
    
	/**
     * Test manager configuration exists
     */
    public function testManagerConfigurationExists()
    {
		$controlContainer = $this->generateName();
		
		$storageClient = $this->createStorageInstance();
        $manager = new Zend_Service_WindowsAzure_Diagnostics_Manager($storageClient, $controlContainer);
        
        $result = $manager->configurationForRoleInstanceExists('test');
        $this->assertFalse($result);
        
        $configuration = $manager->getDefaultConfiguration();
        $manager->setConfigurationForRoleInstance('test', $configuration);
        
        $result = $manager->configurationForRoleInstanceExists('test');
        $this->assertTrue($result);
    }
}
