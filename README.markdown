# Zend Framework extensions

### View helpers

application.ini

    resources.view.helperPath.Ext_View_Helper_ = BASE_PATH "/library/Ext/View/Helper"

#### Ext_View_Helper_Breadcrumbs

action

    $this->view->breadcrumbs('Main page');
    $this->view->breadcrumbs('About', 'about', array('uri' => 'about.html'));

view or layout

    <?= $this->breadcrumbs()->render() ?>

#### Ext_View_Helper_IsActive

view or layout

    <a href="<link>" <?php if ($this->isActive('module:controller:action', 'module:controller', 'module') ?>class="active"<? endif ?>>anhor</a>

module - required, controller and action - optional

### Application resources

#### Ext_Application_Resource_Exceptionizer

see [PHP_Exceptionizer](http://dklab.ru/lib/PHP_Exceptionizer/)

application.ini

    pluginPaths.Ext_Application_Resource = BASE_PATH "/library/Ext/Application/Resource"
    resources.exceptionizer.params.mask = E_ALL

### Controller Helpers

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

#### Ext_File

    // local transfer

    // application.ini
    // configurator class must be extends Ext_File_Adapter_Configurator_Abstract
    resources.transfer.adapter.params.destination = 'path/to/move/uploaded/files' // if local adapter

    // form proccessing
    $form->getElement('file')->setConfigurator(new Ext_File_Configurator_HttpPost); // you need use custom configurator
    $value = $form->getValue('file');
