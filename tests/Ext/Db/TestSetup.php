<?php

class Ext_Db_TestSetup extends Zend_Db_TestSetup
{
    public function getDriver()
    {
        return 'Pdo_Pgsql';
    }
}