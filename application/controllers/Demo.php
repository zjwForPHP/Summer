<?php
use EasyWeChat\Factory;
use Api\CouponModel;

class DemoController extends Yaf\Controller_Abstract {

    /**
     * 初始化执行，关闭视图
     */
    public function init(){

        Yaf\Dispatcher::getInstance()->disableView();
    }

    /**
     *  demo: redis用法
     */
    public function redisAction()
    {
        $config = Yaf\Registry::get("config");

        $redis = new Redis();
        //连接
        $redis->connect($config->redis->host, $config->redis->port);
        //链接密码,默认为零
        $redis->auth($config->redis->pwd);
        //检测是否连接成功
        echo "Server is running: " . $redis->ping();
    }

    /**
     *  demo: 模型用法
     */
    public function modelAction()
    {
        $coupon = new CouponModel();

        $res = $coupon->getCouponInfo(1);

        vd($res);
    }

    /**
     * demo: 一个完整的demo
     */
    public function indexAction()
    {
        $config = Yaf\Registry::get("config");

        $redis = new Redis();

        $redis->connect($config->redis->host, $config->redis->port);

        $redis->auth($config->redis->pwd);//my redis password

        if($redis->get('list')){

            vd(unserialize($redis->get('list')));
        }else{
            $coupon = new CouponModel();

            $res = $coupon->getCouponInfo(1);

            $redis ->setex( "list" , 300,serialize($res));

            vd($res);
        }

    }
    

}