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

require 'helper_load.php';

define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */

_initTcpConfig();
$config_info = _initConfig();


class tcp_server_start
{


    public function __construct($config_info) {
        $serv =  new Swoole\Server($config_info['TcpServer']['host'], $config_info['TcpServer']['port']);

        $serv->set(
            array(
                'worker_num' => $config_info['server']['worker_num'],
                'log_file' => $config_info['server']['log_file']
            )
        );



        //监听连接进入事件
        $serv->on('Connect', function ($serv, $fd) {
            TcpServer::deal_connect($serv, $fd);
        });

        //监听数据接收事件
        $serv->on('Receive', function ($serv, $fd, $from_id, $data) {
            TcpServer::deal_receive($serv, $fd, $from_id, $data);
        });

        //监听连接关闭事件
        $serv->on('Close', function ($serv, $fd) {
            TcpServer::deal_close($serv, $fd);
        });

        $serv->start();
    }

}

// 启动 server

$run = new tcp_server_start($config_info);