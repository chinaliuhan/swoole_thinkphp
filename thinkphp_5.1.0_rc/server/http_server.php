<?php
/**
 * @Author: liuhao
 * @Date  :   2018/9/24
 * @Time  :   02:36
 * @File  :   http_server.php
 * @Desc  : ...
 */
$http = new swoole_http_server('0.0.0.0', '8811');
//这里设置静态后,如果用户访问的是静态页面,在这里命中文件,后面的就不会再执行了,
$http->set([
    'enable_static_handler' => true,    //开启静态页面支持
//    'document_root'         => '/workspace/imooc_swoole/thinkphp_5.1.0_rc/public/static',   //指定静态页面路径
    'document_root'         => '/Users/liuhao/workspace/myProject/imooc_swoole/thinkphp_5.1.0_rc/public/static',   //指定静态页面路径
    "worker_num"            => 5,
]);
//worker进程启动时的回调
$http->on('WorkerStart', function (swoole_server $server, $workId) {
    //加载框架中的文件
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    // 加载基础文件, 这里不能使用start.php 要不然引导文件会被每个worker进程加载, 每次访问都会处理5次
    require __DIR__ . '/../thinkphp/base.php';
    // 加载框架引导文件
    //        require __DIR__ . '/../thinkphp/start.php';
});
$http->on('request', function ($request, $response) use ($http) {

    $_SERVER = [];
    if (isset($request->server)) {
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    if (isset($request->header)) {
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    $_GET = [];
    if (isset($request->get)) {
        foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
        }
    }
    $_POST =[];
    if (isset($request->post)) {
        foreach ($request->post as $k => $v) {
            $_POST[$k] = $v;
        }
    }
    ob_start();
    try {
        think\Container::get('app', [APP_PATH])
                       ->run()
                       ->send();
    }catch (Exception $e){

    }
    $result = ob_get_contents();
    ob_end_clean();
    $response->end($result);
});
$http->start();