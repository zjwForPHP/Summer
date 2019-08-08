<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract{

    /**
     *  初始化配置，并将配置储存
     */
    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $arrConfig);

    }

    /**
     *  初始化错误等级
     */
    public function _initErrorConfig() {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL );
    }


    /**
     * 初始化自建方法自动加载
     */
    public function _initCommonFunctions(){
        Yaf\Loader::import(Yaf\Application::app()->getConfig()->application->directory . '/common/functions.php');
    }

    /**
     *  使用composer作为扩展管理
     */
    public function _initComposerAutoLoad(){
        $arrConfig = Yaf\Registry::get('config');
        if($arrConfig->composer->autoload == 1)require '../vendor/autoload.php';
    }

}
