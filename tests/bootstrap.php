<?php

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../lib/'));

spl_autoload_register('autoload');

function autoload($class)
{
    $filepath = implode('/', explode('_', $class)) . '.php';
    include $filepath;
}

function dumper($var)
{
    print_r($var); exit();
}