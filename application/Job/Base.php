<?php

/**
 * 消息列队消费端
 */
class Base extends Kernel implements Job
{

    protected $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    public function handle()
    {

        if(in_array($this->array['tack_name'],$this->JobList))
        {

            $dealClassName = $this->array['tack_name'];

            $dealOb = new $dealClassName($this->array);

            $dealOb->handle();

        }
    }
}