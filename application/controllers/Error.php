<?php

/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class ErrorController extends Yaf\Controller_Abstract
{
    /**
     * 此时可通过$request->getException()获取到发生的异常
     */

    public function errorAction($exception)
    {
        /* error occurs */
        switch ($exception->getCode()) {
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                $msg = 'emm...我们捕获了一个错误信息，如下';
                $msg .= 'Error:'.$exception->getMessage()."\n";
                $msg.= $exception->getTraceAsString()."\n";
                $msg.= '异常行号：'.$exception->getLine()."\n";
                $msg.= '所在文件：'.$exception->getFile()."\n";
                code($msg);
                break;
            default :
                $msg = "500 \n";
                $msg .= 'Error:'.$exception->getMessage()."\n";
                code($msg);
                break;
        }
    }
}