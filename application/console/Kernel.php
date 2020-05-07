<?php
namespace Console\Kernel;
class Kernel extends \Yaf\Bootstrap_Abstract{

    protected $param_arr;
    protected $param_count;

    public function __construct($param_arr,$param_count)
    {
        $this->param_arr = $param_arr;
        $this->param_count = $param_count;
    }


    public function outPut(){
        if($this->param_count <= 1){
            return "缺失一些参数，请检查\n";
        }else{

            require_once(APP_PATH.'/application/console/Commands/'.$this->param_arr[1].'.php');

            $className = 'Console\Commands\\'.$this->param_arr[1];

            if(class_exists($className)){

                $outOb = new $className;

                $outOb->handle($this->param_arr);

                return "Done!\n";
            }else{
                return "执行失败，请检查是否存在该命令\n";
            }
        }
    }


}