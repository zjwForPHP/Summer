<?php
use EasyWeChat\Factory;
use Api\CouponModel;

class ApiController extends Yaf\Controller_Abstract {

    /**
     * 初始化执行，关闭视图
     */
    public function init(){

        Yaf\Dispatcher::getInstance()->disableView();
    }

    /**
     *  默认的执行方法
     */
    public function indexAction() {
        echo 'Hi ~ MidSummer';
    }

}