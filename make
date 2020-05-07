#!/usr/bin/env php
<?php

define('MAKE_START', date('Y-m-d H:i:s'));

define("APP_PATH",  realpath(dirname(__FILE__) . '/'));


require __DIR__.'/application/console/Kernel.php';

/*
|--------------------------------------------------------------------------
| Register The make scaffold
|--------------------------------------------------------------------------
|    创建一个通用的脚手架文件
*/

$outOb = new Console\Kernel\Kernel($argv,$argc);

exit($outOb->outPut());