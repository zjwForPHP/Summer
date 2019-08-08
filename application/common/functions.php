<?php
/**
 * 自定义函数
 * Created by morty
 * Date: 2018/3/26
 * Time: 15:53
 */

/**
 * 符合人类视觉美工输出
 * @author：BJY
 * @param $data
 */
if(!function_exists('vd'))
{
    function vd($data){
    // 定义样式
        $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data=$data ? 'true' : 'false';
        }elseif (is_null($data)) {
            $show_data='null';
        }else{
            $show_data=print_r($data,true);
        }
        $str.=$show_data;
        $str.='</pre>';
        echo $str;
    }
}

if(!function_exists('code'))
{
    function code($data){
        // 定义样式
        $str='<pre style="display: block;background: none repeat scroll 0 0;background-color: #555555;border-radius:4px 4px 4px 4px;box-shadow: rgba(0,0.25) 0px 0px 10px inset;clear: both;font-family: \'Consolas\',\'Courier\',\'Monaco\',monospace;color: #fff;margin: 5px 0px;overflow: auto;padding: 10px;white-space: pre;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data=$data ? 'true' : 'false';
        }elseif (is_null($data)) {
            $show_data='null';
        }else{
            $show_data=print_r($data,true);
        }
        $str.=$show_data;
        $str.='</pre>';
        echo $str;
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
if(!function_exists('list_to_tree'))
{
    function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
if(!function_exists('tree_to_list'))
{
    function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
        if(is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if(isset($reffer[$child])){
                    unset($reffer[$child]);
                    tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = list_sort_by($list, $order, $sortby='asc');
        }
        return $list;
    }

}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
if(!function_exists('list_sort_by'))
{
    function list_sort_by($list,$field, $sortby='asc') {
        if(is_array($list)){
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ( $refer as $key=> $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }
}

/**
 * 面包屑提供
 * @return  HTML Sting HTML代码
 */
if(!function_exists('breadcrumbs'))
{
    function breadcrumbs()
    {
        $url = url()->current();

        $urlArray = explode('/',substr($url,last_strripos($url,'/',2)+1));

        $control = $urlArray[0];

        $action = $urlArray[1];

        $info = \App\Model\Menu::where([['control','=',$control],['action','=',$action]])->first();

        if($info->parent == null)
        {
            return  '<li><a href="#"><i class="fa fa-dashboard"></i> 首页 </a></li>
            <li class="active">'.$info->name.'</li>';
        }else{
            $parentInfo = \App\Model\Menu::where('id','=',$info->parent)->first();

            return  '<li><a href="#"><i class="fa fa-dashboard"></i> '.$parentInfo->name.' </a></li>
            <li class="active">'.$info->name.'</li>';
        }

    }
}


/**
 * 倒数第$num次出现的位置
 * @str  被寻找的字符串
 * @find 寻找的字符串
 * @num  倒数第几次
 * @offset 已知的偏移量
 */
if(!function_exists('last_strripos'))
{
    function last_strripos($str,$find,$num,$offset=0)
    {
        $pos = strripos($str, $find, $offset);
        $num--;
        if ($num > 0 && $pos !== FALSE)
        {
            $tepStr = substr($str,0,$pos);
            $pos=  last_strripos($tepStr,$find,$offset);
        }
        return $pos;
    }
}


/**
 * 获取隐式路由的控制器和方法
 */
if(!function_exists('get_control_action'))
{
    function get_control_action():array
    {
        $url = url()->current();

        $urlArray = explode('/',substr($url,last_strripos($url,'/',2)+1));

        return  $urlArray;
    }
}



/**

 * 	作用：将xml转为array

 */
if(!function_exists('xmlToArray'))
{
    function xmlToArray($xml):array
    {
        //libxml_disable_entity_loader(true);
        //将XML转为array

        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $array_data;

    }
}


/**
 * 解析xml
 */
if(!function_exists('xml_parser'))
{
    function xml_parser($str){
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser,$str,true)){
            xml_parser_free($xml_parser);
            return false;
        }else {
            return (json_decode(json_encode(simplexml_load_string($str)),true));
        }
    }
}


if(!function_exists('postXmlCurl'))
{
    function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl

        $ch = curl_init();

        //设置超时

        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //这里设置代理，如果有的话

        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');

        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);

        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

        //设置特殊端口

        //curl_setopt($ch, CURLOPT_PORT, $port);

        //设置header
        $headers = array();

        $headers[] = 'Content-Type: text/xml; charset=utf-8';

        //要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


        //post提交方式

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        //运行curl

        $data = curl_exec($ch);

        //curl_close($ch);

        //返回结果

        if($data)

        {

            curl_close($ch);

            return $data;

        }

        else

        {

            $error = curl_errno($ch);

            echo "curl出错，错误码:$error"."<br>";

            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";

            curl_close($ch);

            return false;

        }
    }
}

if(!function_exists('postTextCurl'))
{
    function postTextCurl($xml,$url,$second=30)
    {
        //初始化curl

        $ch = curl_init();

        //设置超时

        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //这里设置代理，如果有的话

        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');

        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);

        curl_setopt($ch,CURLOPT_URL, $url);

        //设置特殊端口


        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

        //设置header
        $headers = array();

        $headers[] = 'Content-Type: text/plain; charset=utf-8';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        //要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        //运行curl

        $data = curl_exec($ch);

        //curl_close($ch);

        //返回结果

        if($data)

        {

            curl_close($ch);

            return $data;

        }

        else

        {

            $error = curl_errno($ch);

            echo "curl出错，错误码:$error"."<br>";

            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";

            curl_close($ch);

            return false;

        }
    }
}


if(!function_exists('postXmlSSLCurl'))
{
    /**

     * 	作用：使用证书，以post方式提交xml到对应的接口url

     */
    function postXmlSSLCurl($xml,$url,$second=30)

    {

        $ch = curl_init();

        //超时时间

        curl_setopt($ch,CURLOPT_TIMEOUT,$second);

        //这里设置代理，如果有的话

        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');

        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);

        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

        //设置header

        curl_setopt($ch,CURLOPT_HEADER,FALSE);

        //要求结果为字符串且输出到屏幕上

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        //设置证书

        //使用证书：cert 与 key 分别属于两个.pem文件

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');

        curl_setopt($ch,CURLOPT_SSLCERT,'/usr/local/nginx/cert/wxlifepay/214928769660586.pem');

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'KEY');

        curl_setopt($ch,CURLOPT_SSLKEY,'/usr/local/nginx/cert/wxlifepay/214928769660586.key');

        // post提交方式

        curl_setopt($ch,CURLOPT_POST, true);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

        $data = curl_exec($ch);

        //返回结果

        if($data){

            curl_close($ch);

            return $data;

        }else{

            $error = curl_errno($ch);

            echo "curl出错，错误码:$error"."<br>";

            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";

            curl_close($ch);

            return false;

        }

    }
}



if(!function_exists('https'))
{
    /**
     * 模拟提交参数，支持https提交 可用于各类api请求
     * @param string $url ： 提交的地址
     * @param array $data :POST数组
     * @param string $method : POST/GET，默认GET方式
     * @return mixed
     */
    function https($url, $data='', $method='GET'){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0"); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if($method=='POST'){
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != ''){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}


if(!function_exists('https_request'))
{
    function https_request($url, $data = null,$noprocess=false) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");
    $header = array("Accept-Charset: utf-8");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    //curl_setopt($curl, CURLOPT_SSLVERSION, 3);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); /* * *$header 必须是一个数组** */
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    if($noprocess) return $output;
    $errorno = curl_errno($curl);
    if ($errorno) {
        return array('curl' => false, 'errorno' => $errorno);
    } else {
        $res = json_decode($output, 1);
        if ($res['errcode']) {
            return array('errcode' => $res['errcode'], 'errmsg' => $res['errmsg']);
        } else {
            return $res;
        }
    }
    curl_close($curl);
}
}


if(!function_exists('verifier_sign'))
{
    function verifier_sign($xmlString)
    {
        $num = stripos($xmlString,'<');
        $xml = substr($xmlString,$num);
        $weChat_code = substr($xmlString,0,$num);

        $location_code = encryption_xml($xml);
        if($weChat_code == $location_code)
        {
            return $xml;
        }else{
            return false;
        }
    }
}



if(!function_exists('encryption_xml'))
{
    function encryption_xml($xml)
    {
        $code = sha1($xml.env('API_SECRETKEY'));
        $code = strtoupper($code);
        return $code;
    }
}


if(!function_exists('triggerRequest'))
{
    function triggerRequest($url, $post_data = array(), $cookie = array()){
        $method = "GET";  //可以通过POST或者GET传递一些参数给要触发的脚本
        $url_array = parse_url($url); //获取URL信息，以便平凑HTTP HEADER
        $port = isset($url_array['port'])? $url_array['port'] : 80;

        $fp = fsockopen($url_array['host'], $port, $errno, $errstr, 30);
        if (!$fp){
            return FALSE;
        }
        if(isset($url_array['query'])){
            $getPath = $url_array['path'] ."?". $url_array['query'];
        }else{
            $getPath = $url_array['path'];
        }

        if(!empty($post_data)){
            $method = "POST";
        }
        $header = $method . " " . $getPath;
        $header .= " HTTP/1.1\r\n";
        $header .= "Host: ". $url_array['host'] . "\r\n"; //HTTP 1.1 Host域不能省略
        /**//*以下头信息域可以省略
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 \r\n";
        $header .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,q=0.5 \r\n";
        $header .= "Accept-Language: en-us,en;q=0.5 ";
        $header .= "Accept-Encoding: gzip,deflate\r\n";
         */

        $header .= "Connection:Close\r\n";
        if(!empty($cookie)){
        $_cookie = strval(NULL);
        foreach($cookie as $k => $v){
            $_cookie .= $k."=".$v."; ";
        }
                $cookie_str =  "Cookie: " . base64_encode($_cookie) ." \r\n";//传递Cookie
                $header .= $cookie_str;
        }
        if(!empty($post_data)){
            $_post = strval(NULL);
            foreach($post_data as $k => $v){
                $_post .= $k."=".$v."&";
            }
            $post_str  = "Content-Type: application/x-www-form-urlencoded\r\n";//POST数据
            $post_str .= "Content-Length: ". strlen($_post) ." \r\n";//POST数据的长度
            $post_str .= $_post."\r\n\r\n "; //传递POST数据
            $header .= $post_str;
        }
        fwrite($fp, $header."\r\n");
        //echo fread($fp, 1024); //我们不关心服务器返回
        fclose($fp);
        return true;
    }

    if(!function_exists('http_get')){
        function http_get($url){
            $oCurl = curl_init();
            if(stripos($url,"https://")!==FALSE){
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
            }
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
            $sContent = curl_exec($oCurl);
            $aStatus = curl_getinfo($oCurl);
            curl_close($oCurl);
            if(intval($aStatus["http_code"])==200){
                return $sContent;
            }else{
                return false;
            }
        }
    }
}


if(!function_exists('xml_package_error'))
{
    function xml_package_error($array)
    {
        //打包数据组成XML
        $headXml = '<?xml version="1.0" encoding="UTF-8"?><wxlifepay><head><version>1.0.1</version><trancode>'.$array['trancode'].'</trancode><transeqnum>'.$array['transeqnum'].'</transeqnum><merchantid>'.env('SHJF_MERCHANT_ID').'</merchantid><ret_code>'.$array['ret_code'].'</ret_code><err_msg>'.$array['err_msg'].'</err_msg><head><info></info><wxlifepay>';
        //生成签名
        $code = encryption_xml($headXml);
        return $code.$headXml;
    }
}


if(!function_exists('error_tip'))
{
    function error_tip($message,$jumpUrl='',$waitTime=3)
    {
        $returnArray = array(
            'message'=>$message,
            'jumpurl'=>$jumpUrl?:url()->current(),
            'waittime'=>$waitTime
        );


        return view('errors.problem',$returnArray);
    }
}

if(!function_exists('tail'))
{
    function tail($file,$num)
    {
        $fp = fopen($file,"r");
        $pos = -2;
        $eof = "";
        $head = false;   //当总行数小于Num时，判断是否到第一行了
        $lines = array();

        while($num>0)
        {
                while($eof != "\n")
                {
                    if(fseek($fp, $pos, SEEK_END)==0){
                        //fseek成功返回0，失败返回-1
                        $eof = fgetc($fp);
                        $pos--;
                    }else{
                        //当到达第一行，行首时，设置$pos失败
                        fseek($fp,0,SEEK_SET);
                        $head = true;
                        //到达文件头部，开关打开
                        break;
                    }
                }
                array_unshift($lines,fgets($fp));
                if($head){ break; }                 //这一句，只能放上一句后，因为到文件头后，把第一行读取出来再跳出整个循环
                $eof = "";
                $num--;
        }

        fclose($fp);
        return $lines;
    }

}

/**
 * 发送json http请求
 */
if(!function_exists('json_https'))
{
    function json_https($url, $data='', $method='GET'){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0"); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if($method=='POST'){
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != ''){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}


/**
 * 数组转换成json
 */
if(!function_exists('array_to_json'))
{
    function array_to_json($array)
    {
        return json_encode($array,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}


/**
 * 获取HTTP头部句柄
 */
if(!function_exists('get_url_header'))
{
    function get_url_header($url)
    {
        $ch = curl_init(); // create cURL handle (ch)
        if (!$ch) {
            die("Couldn't initialize a cURL handle");
        }
        // set some cURL options
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        $ret = curl_setopt($ch, CURLOPT_HEADER,1);
        $ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
        $ret = curl_setopt($ch, CURLOPT_TIMEOUT,30);  //超时30秒

        // execute
        $ret = curl_exec($ch);

        if (empty($ret)) {
            // some kind of an error happened
            $return_array = false;
            curl_close($ch); // close cURL handler
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler

            if (empty($info['http_code'])) {
                $return_array = false;
            } else {
                $return_array = $info;
            }

        }

        return $return_array;
    }
}