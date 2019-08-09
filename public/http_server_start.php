<?php
/**
 * MidSummer - A High Concurrency PHP Framework For RESTful-API
 * @basie    yaf+swoole
 * @package  Summer
 * @author   Morty <http://zhujunwei.top>
 * @type     同步阻塞HTTP服务器 <多线程+多协程>
 * 服务文件，没有特殊情况请不要修改
 * 启动服务前请检查是否已经将服务器处理php的权力交给了对应端口。
 * 温馨提示：修改了代码调试需要停止服务，再重启才能看到效果，所以建议，先用yaf模式调试好后再启用Mid模式
 */

require 'helper_load.php';

define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */

$config_info = _initConfig();


class http_server_start
{
    public static $instance;

    public $http;
    public static $get;
    public static $post;
    public static $header;
    public static $server;
    private $application;

    public function __construct($config_info) {
        $http =  new Swoole\Http\Server($config_info['HttpServer']['host'], $config_info['HttpServer']['port']);

        $http->set(
            array(
                'worker_num' => $config_info['server']['worker_num'],
                'max_request' => $config_info['server']['max_request'],
                'dispatch_mode' => $config_info['server']['dispatch_mode'],
                'log_file' => $config_info['server']['log_file']
            )
        );

        // 启动前准备
        $http->on('WorkerStart', function ($request, $response) {

            go(function () {
                $this->application  = new Yaf\Application(APP_PATH . "/conf/application.ini");
                ob_start();
                $this->application->bootstrap()->run();
                ob_end_clean();
            });

        });

        // 将请求转发给yaf处理
        $http->on('request', function ($request, $response) {

            go(function () use($request,$response) {
                if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
                    $response->status(404);
                    $response->end();
                }

                if( isset($request->server) ) {
                    self::$server = $request->server;
                }
                if( isset($request->header) ) {
                    self::$header = $request->header;
                }
                if( isset($request->get) ) {
                    self::$get = $request->get;
                }
                if( isset($request->post) ) {
                    self::$post = $request->post;
                }

                // TODO handle img

                ob_start();
                try {
                    $yaf_request = new Yaf\Request\Http(
                        self::$server['request_uri']);

                    $this->application
                        ->getDispatcher()->dispatch($yaf_request);

                    // unset(Yaf_Application::app());
                } catch ( Yaf_Exception $e ) {
                    var_dump( $e );
                }

                $result = ob_get_contents();

                ob_end_clean();

                $response->header('Content-Type', 'text/html; charset=utf8');//输出为utf-8编码模式

                $response->end($result);
            });

        });

        $http->start();
    }

}

// 启动 server

$run = new http_server_start($config_info);