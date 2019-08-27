<?php

/**
 * 消息列队消费端--逻辑类
 */
class Demo extends Base
{
    public function handle()
    {

        file_put_contents('test.log','时间：'.date('His').' '.serialize($this->array)."\n",FILE_APPEND);
    }
}