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