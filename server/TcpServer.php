<?php
/**
 * Class TcpServer  tcp服务的代码需要在这里写，本类中支持composer，与pdo操作
 * @author Morty <zhujunwei.top>
 * $server 为swoole注入服务
 * $fd     为客户端连接的唯一标识符
 */
class TcpServer
{
    /**处理链接时
     * @param $server
     * @param $fd
     */
    public static function deal_connect($server, $fd){
        echo "Client: Connect.\n";
    }

    /** 处理接受到数据时
     * @param $server
     * @param $fd
     * @param $from_id
     * @param $data   接受的数据
     */
    public static function deal_receive($server, $fd, $from_id, $data){
        $server->send($fd, "Server: ".$data);
    }


    /**客户端链接关闭事件
     * @param $server
     * @param $fd
     */
    public static function deal_close($server, $fd){
        echo "Client: Close.\n";
    }
}