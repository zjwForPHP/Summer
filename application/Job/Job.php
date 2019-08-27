<?php

/**
 * 接口Job
 * Interface Job
 */
interface Job
{
    public function __construct($array);

    public function handle();
}