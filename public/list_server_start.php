<?php
/**
 * MidSummer - A High Concurrency PHP Framework For RESTful-API
 * @basie    yaf+swoole
 * @package  Summer
 * @author   Morty <http://zhujunwei.top>
 * @type     异步非阻塞TCP服务器 <多线程+多协程>
 * 服务文件，没有特殊情况请不要修改
 * 启动服务前请检查是否已经将服务器处理php的权力交给了对应端口。
 * 温馨提示：修改了代码调试需要停止服务，再重启才能看到效果
 */
use Swoole\Redis\Server;
use Swoole\Coroutine\Redis;

define("APP_PATH",  realpath(dirname(__FILE__) . '/../'));
define("LOAD_JOB",  1);

require 'helper_load.php';

$config_info = _initConfig();


class list_server_start
{


    public function __construct($config_info) {
        $serv = new Server($config_info['list']['host'], $config_info['list']['port'], SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        $serv->setHandler('lpush', function ($fd, $data) use ($serv) {
            $cli = new Redis();
            $cli->connect('127.0.0.1', 6379);
            $dataArray = json_decode($data[1],true);
            Swoole\Coroutine::sleep($dataArray['delayTime']);
            // 业务逻辑
            (new Base($dataArray))->handle();

            $serv->send($fd, Server::format(Server::INT, 0));

        });

        $serv->start();
    }

}

// 启动 server

$run = new list_server_start($config_info);

/*use Swoole\Redis\Server;
use Swoole\Coroutine\Redis;

$serv = new Server('127.0.0.1', 10086, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
$serv->setHandler('lpush', function ($fd, $data) use ($serv) {
    $cli = new Redis();
    $cli->connect('127.0.0.1', 6379);
    $dataArray  = json_decode($data,true);
    $res = $cli->rpop($dataArray['tack_name']);
    if($res){
        Swoole\Coroutine::sleep(1);
        // 业务逻辑
        (new Job_Base($data))->handle();

        $serv->send($fd, Server::format(Server::INT, 0));
    }else{
        $serv->send($fd, Server::format(Server::INT, 99));
    }

});


$serv->start();*/