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
 * @package    Zend_Service_Nirvanix
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ExceptionTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * @see Zend_Service_Nirvanix_Exception
 */
require_once 'Zend/Service/Nirvanix/Exception.php';

/**
 * @category   Zend
 * @package    Zend_Service_Nirvanix
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Service
 * @group      Zend_Service_Nirvanix
 */
class Zend_Service_Nirvanix_ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testInheritsFromZendException()
    {
        $exc = new Zend_Service_Nirvanix_Exception();
        $this->assertType('Zend_Exception', $exc);
    }
}
