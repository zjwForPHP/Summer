<?php
namespace Console\Commands;

class BuildController{

    public function handle($param_arr)
    {
        // 生成标准class
        $className = ucfirst($param_arr[2]);
        $classNameSpace = "namespace Controller\\".$className.";";
        $classNameString = "class {$className}Controller extends Yaf\Controller_Abstract";
        $classBody = <<<CLASSBODY
{

    /**
     * 初始化执行，关闭视图
     */
    public function init()
    {

        Yaf\Dispatcher::getInstance()->disableView();
    }

    /**
     *  默认的执行方法
     */
    public function indexAction()
    {
        echo 'Hi ~ MidSummer';
    }

}
CLASSBODY;

        $content = "<?php\n".$classNameSpace."\n".$classNameString."\n".$classBody;

        $filename =APP_PATH.'/application/controllers/'.$className.'.php';

        file_put_contents($filename,$content);

    }
}