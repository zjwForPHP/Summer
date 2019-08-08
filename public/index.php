<?php

/**
 * Summer - A PHP Framework For RESTful-API
 * @basie    yaf
 * @package  Summer
 * @author   Morty <http://zhujunwei.top>
 * 入口文件，没有特殊情况请不要修改
 */


date_default_timezone_set("Asia/Shanghai");

if (!defined('__ROOT__')) {
    $_root = rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
    define('__ROOT__', (('/' == $_root || '\\' == $_root) ? '' : $_root));
}

define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */

//开发环境 product：线上环境；develop：线下开发环境
define('APP_ENV', 'develop');


$app  = new Yaf\Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()->run();
