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
 * @package    Zend_Navigation
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: PageFactoryTest.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Navigation/Page.php';

/**
 * Tests Zend_Navigation_Page::factory()
 *
/**
 * @category   Zend
 * @package    Zend_Navigation
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Navigation
 */
class Zend_Navigation_PageFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $_oldIncludePath;

    protected function setUp()
    {
        // store old include path
        $this->_oldIncludePath = get_include_path();

        // add _files dir to include path
        $addToPath = dirname(__FILE__) . '/_files';
        set_include_path($addToPath . PATH_SEPARATOR . $this->_oldIncludePath);
    }

    protected function tearDown()
    {
        // reset include path
        set_include_path($this->_oldIncludePath);
    }

    public function testDetectMvcPage()
    {
        $pages = array(
            Zend_Navigation_Page::factory(array(
                'label' => 'MVC Page',
                'action' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label' => 'MVC Page',
                'controller' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label' => 'MVC Page',
                'module' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label' => 'MVC Page',
                'route' => 'home'
            ))
        );

        $this->assertContainsOnly('Zend_Navigation_Page_Mvc', $pages);
    }

    public function testDetectUriPage()
    {
        $page = Zend_Navigation_Page::factory(array(
            'label' => 'URI Page',
            'uri' => '#'
        ));

        $this->assertType('Zend_Navigation_Page_Uri', $page);
    }

    public function testMvcShouldHaveDetectionPrecedence()
    {
        $page = Zend_Navigation_Page::factory(array(
            'label' => 'MVC Page',
            'action' => 'index',
            'controller' => 'index',
            'uri' => '#'
        ));

        $this->assertType('Zend_Navigation_Page_Mvc', $page);
    }

    public function testSupportsMvcShorthand()
    {
        $mvcPage = Zend_Navigation_Page::factory(array(
            'type' => 'mvc',
            'label' => 'MVC Page',
            'action' => 'index',
            'controller' => 'index'
        ));

        $this->assertType('Zend_Navigation_Page_Mvc', $mvcPage);
    }

    public function testSupportsUriShorthand()
    {
        $uriPage = Zend_Navigation_Page::factory(array(
            'type' => 'uri',
            'label' => 'URI Page',
            'uri' => 'http://www.example.com/'
        ));

        $this->assertType('Zend_Navigation_Page_Uri', $uriPage);
    }

    public function testSupportsCustomPageTypes()
    {
        $page = Zend_Navigation_Page::factory(array(
            'type' => 'My_Page',
            'label' => 'My Custom Page'
        ));

        return $this->assertType('My_Page', $page);
    }

    public function testShouldFailForInvalidType()
    {
        try {
            $page = Zend_Navigation_Page::factory(array(
                'type' => 'My_InvalidPage',
                'label' => 'My Invalid Page'
            ));
        } catch(Zend_Navigation_Exception $e) {
            return;
        }

        $this->fail('An exception has not been thrown for invalid page type');
    }

    public function testShouldFailForNonExistantType()
    {
        $pageConfig = array(
            'type' => 'My_NonExistant_Page',
            'label' => 'My non-existant Page'
        );

        try {
            $page = Zend_Navigation_Page::factory($pageConfig);
        } catch(Zend_Exception $e) {
            return;
        }

        $msg = 'A Zend_Exception has not been thrown for non-existant class';
        $this->fail($msg);
    }

    public function testShouldFailIfUnableToDetermineType()
    {
        try {
            $page = Zend_Navigation_Page::factory(array(
                'label' => 'My Invalid Page'
            ));
        } catch(Zend_Navigation_Exception $e) {
            return;
        }

        $this->fail('An exception has not been thrown for invalid page type');
    }
}
