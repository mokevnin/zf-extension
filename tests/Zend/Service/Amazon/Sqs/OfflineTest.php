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
 * @package    Zend_Service_Amazon
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: OfflineTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * @category   Zend
 * @package    Zend_Service_Amazon
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Service
 * @group      Zend_Service_Amazon
 * @group      Zend_Service_Amazon_Sqs
 */
class Zend_Service_Amazon_Sqs_OfflineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->markTestSkipped('No offline tests for Zend_Service_Amazon_Sqs');
    }

    public function testNothing()
    {
    }
}
