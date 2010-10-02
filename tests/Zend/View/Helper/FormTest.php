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
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

// Call Zend_View_Helper_FormTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_FormTest::main");
}

require_once dirname(__FILE__) . '/../../../TestHelper.php';

require_once 'Zend/View.php';
require_once 'Zend/View/Helper/Form.php';

/**
 * Test class for Zend_View_Helper_Form.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Zend_View_Helper_FormTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        $this->view = new Zend_View();
        $this->helper = new Zend_View_Helper_Form();
        $this->helper->setView($this->view);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
    }

    public function testFormWithSaneInput()
    {
        $form = $this->helper->form('foo', array('action' => '/foo', 'method' => 'get'));
        $this->assertRegexp('/<form[^>]*(id="foo")/', $form);
        $this->assertRegexp('/<form[^>]*(action="\/foo")/', $form);
        $this->assertRegexp('/<form[^>]*(method="get")/', $form);
    }

    public function testFormWithInputNeedingEscapesUsesViewEscaping()
    {
        $form = $this->helper->form('<&foo');
        $this->assertContains($this->view->escape('<&foo'), $form);
    }

    public function testPassingIdAsAttributeShouldRenderIdAttribAndNotName()
    {
        $form = $this->helper->form('foo', array('action' => '/foo', 'method' => 'get', 'id' => 'bar'));
        $this->assertRegexp('/<form[^>]*(id="bar")/', $form);
        $this->assertNotRegexp('/<form[^>]*(name="foo")/', $form);
    }

    /**
     * @see ZF-3832
     */
    public function testEmptyIdShouldNotRenderIdAttribute()
    {
        $form = $this->helper->form('', array('action' => '/foo', 'method' => 'get'));
        $this->assertNotRegexp('/<form[^>]*(id="")/', $form);
        $form = $this->helper->form('', array('action' => '/foo', 'method' => 'get', 'id' => null));
        $this->assertNotRegexp('/<form[^>]*(id="")/', $form);
    }
}

// Call Zend_View_Helper_FormTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_FormTest::main") {
    Zend_View_Helper_FormTest::main();
}
