# Zend Framework extensions

### View helpers

#### Ext_View_Helper_Breadcrumbs

application.ini

    resources.view.helperPath.Ext_View_Helper_ = BASE_PATH "/library/Ext/View/Helper"

action

    $this->view->breadcrumbs('Main page');
    $this->view->breadcrumbs('About', 'about', array('uri' => 'about.html'));

layout

    <?= $this->breadcrumbs()->render() ?>

#### Ext_Application_Resource_Exceptionizer

see [PHP_Exceptionizer](http://dklab.ru/lib/PHP_Exceptionizer/)

application.ini

    pluginPaths.Ext_Application_Resource = BASE_PATH "/library/Ext/Application/Resource"
    resources.exceptionizer.params.mask = E_ALL

#### Ext_Controller_Action_Helper_Di

see [Inversion of Control Containers and the Dependency Injection pattern](http://martinfowler.com/articles/injection.html)

application.ini

    resources.frontcontroller.actionhelperpaths.Ext_Controller_Action_Helper_ = BASE_PATH "/library/Ext/Controller/Action/Helper"

Example

    resources.cachemanager.database.backend.name = Memcached
    ...

controller

    /**
     * @var
     * @inject cachemanager
    /*
    public $cachemanager

#### Ext_View_Helper_IsActive

view

    <a href="<link>" <?php if ($this->isActive('module:controller:action', 'module:controller', 'module') ?>class="active"<? endif ?>>anhor</a>

module - required, controller and action - optional