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
 * @package    Zend_Ldap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ConverterTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * Test helper
 */
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'TestHelper.php';
/**
 * Zend_Ldap_Converter
 */
require_once 'Zend/Ldap/Converter.php';

/**
 * @category   Zend
 * @package    Zend_Ldap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Ldap
 */
class Zend_Ldap_ConverterTest extends PHPUnit_Framework_TestCase
{
    public function testAsc2hex32()
    {
        $expected='\00\01\02\03\04\05\06\07\08\09\0a\0b\0c\0d\0e\0f\10\11\12\13\14\15\16\17\18\19' .
            '\1a\1b\1c\1d\1e\1f !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`' .
            'abcdefghijklmnopqrstuvwxyz{|}~';
        $str='';
        for ($i=0; $i<127; $i++) {
             $str.=chr($i);
        }
        $this->assertEquals($expected, Zend_Ldap_Converter::ascToHex32($str));
    }

    public function testHex2asc()
    {
        $expected='';
        for ($i=0; $i<127; $i++) {
             $expected.=chr($i);
        }

        $str='\00\01\02\03\04\05\06\07\08\09\0a\0b\0c\0d\0e\0f\10\11\12\13\14\15\16\17\18\19\1a\1b' .
            '\1c\1d\1e\1f !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefg' .
            'hijklmnopqrstuvwxyz{|}~';
        $this->assertEquals($expected, Zend_Ldap_Converter::hex32ToAsc($str));
    }
}

