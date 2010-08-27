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