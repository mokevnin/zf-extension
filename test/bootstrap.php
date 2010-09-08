<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../lib/');

spl_autoload_register('autoload');

function autoload($class)
{
    $class_name = implode('/', explode('_', $class)) . '.php';
    include $class_name;
}

function dumper($var)
{
    print_r($var); exit();
}