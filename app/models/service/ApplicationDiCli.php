<?php

namespace Service;


use Phalcon\Di\FactoryDefault\Cli;
use Resource\Config;
use Resource\SytelineDb;

class ApplicationDiCli extends ApplicationDi
{
    public static function factory()
    {
        self::$_di = new Cli();

        parent::factory();
    }
}
