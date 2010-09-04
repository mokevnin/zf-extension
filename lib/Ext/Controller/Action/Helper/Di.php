<?php

/**
 * @category   Ext
 * @package    Ext_Controller
 * @subpackage Ext_Controller_Action_Helper
 * @author     Mokevnin Kirill <mokevnin@gmail.com>
 * @license    New BSD License
 */

/**
 * Simple Dependency Injection
 * @see http://martinfowler.com/articles/injection.html
 */
class Ext_Controller_Action_Helper_Di extends Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch()
    {
        $action_controller = $this->getActionController();
        $class = new Zend_Reflection_Class($action_controller);
        $propertis = $class->getProperties();

        foreach ($propertis as $property) {
            if($property->class != $class->getName()) {
                continue;
            }
            $doc_comment = $property->getDocComment();

            if ($doc_comment && $doc_comment->hasTag('inject')) {
                $inject_tag = $doc_comment->getTag('inject');
                $resource_name = $inject_tag->getDescription();
                $bootstrap = $this->getFrontController()->getParam('bootstrap');
                $bootstrap->bootstrap($resource_name);

                $resource = $bootstrap->getResource($resource_name);
                $property->setValue($action_controller, $resource);
            }
        }
    }
}