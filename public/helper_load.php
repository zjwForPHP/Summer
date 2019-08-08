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