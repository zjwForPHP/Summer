<?php

function _initConfig()
{
    $config_info = parse_ini_file(APP_PATH . "/conf/swoole.ini",true);

    return $config_info;
}

function _initTcpConfig()
{
    require '../vendor/autoload.php';
    require '../server/TcpServer.php';
    require '../application/library/DataBase.php';
}

function loadJob($class){
    $class=str_replace('\\', '/', $class);
    $class="/application/Job/".$class.".php";
    require_once APP_PATH.$class;
}

if(LOAD_JOB == 1)
{
    spl_autoload_register('loadJob');
}