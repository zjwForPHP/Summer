<?php

/**
 * 消息列队生成端
 * 基于swoole全异步redis
 * @author Morty zhu
 */
class EasyList
{
    protected $redis;
    public $ttr = 60;

    public function __construct($host='127.0.0.1',$port='6379')
    {

        try{
            $this->redis =   new Swoole\Coroutine\Redis();

            $this->redis->connect($host, $port);

        }catch (\Exception $e)
        {
            throw new \Exception('redis has not work,check redis status');
        }

    }

    // 消息标准消息结构
    protected function make_list_message($tack_name,$tack_id,$delayTime,$body)
    {
        $return_array = [
            'tack_name'=>$tack_name,
            'tack_id'=>$tack_id,
            'delayTime'=>$delayTime,
            'ttr'=>$this->ttr,
            'timestamp'=>time(),
            'body'=>$body
        ];

        return json_encode($return_array);
    }

    public function dispense($data,$queue = 'tack')
    {
        // 构建列队数据结构

        $tack_id = $queue.date('YmdHis').rand('1000',9999);

        $task_json = $this->make_list_message($queue,$tack_id,1,$data);

        // 入队
        go(function () use($queue,$task_json) {
            $this->redis->lpush($queue,$task_json);
            $this->redis->setDefer();
        });

    }

    public function clean_list($listName)
    {
        while ($this->redis->lrange($listName,0,1) != [])
        {
            $this->redis->lpop($listName);
        }
    }


}