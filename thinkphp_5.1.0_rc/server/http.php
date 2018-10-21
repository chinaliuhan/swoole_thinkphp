<?php

/**
 * @Author: liuhao
 * @Date  :   2018/10/21
 * @Time  :   18:12
 * @File  :   http.php
 * @Desc  : ...
 */
class http
{

    const   HOST = '0.0.0.0';
    const   PORT = '8811';
    private $http = null;

    public function __construct()
    {
        //激活swoole的句柄
        $this->http = new \Swoole\Http\Server(self::HOST, self::PORT);
        $this->http->set([
            'enable_static_handler' => true,
            //开启静态页面支持
            //    'document_root'         => '/workspace/imooc_swoole/thinkphp_5.1.0_rc/public/static',   //指定静态页面路径
            'document_root'         => '/Users/liuhao/workspace/myProject/imooc_swoole/thinkphp_5.1.0_rc/public/static',
            //指定静态页面路径
            'worker_num'            => 4,
            'task_worker_num'       => 4,
            'log_level'             => SWOOLE_LOG_NOTICE,
        ]);
        $this->http->on('workerstart', [$this, 'onWorkerStart']);
        $this->http->on('request', [$this, 'onRequest']);
        //task任务处理的回调
        $this->http->on('task', [$this, 'onTask']);
        //task任务处理完毕的回调
        $this->http->on('finish', [$this, 'onFinish']);
        //激活连接断开的回调
        $this->http->on('close', [$this, 'onClose']);
        //打开服务
        $this->http->start();
    }

    /**
     * Method  onWorkerStart
     *
     * @desc    进程初始化时加载框架
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/21
     * @time    18:19
     *
     * @param $server
     * @param $workerId
     *
     * @return  void
     */
    public function onWorkerStart($server, $workerId)
    {
        define('APP_PATH', __DIR__ . '/../application/');
        //因为下面要用到new \app\common\lib\Task(),所以只能这么写,而且在/index/index/index中返回一个空
        require __DIR__ . '/../thinkphp/start.php';
    }

    /**
     * Method  onRequest
     *
     * @desc    处理客户端请求和响应
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/21
     * @time    18:21
     *
     * @param $request
     * @param $response
     *
     * @return  void
     */
    public function onRequest($request, $response)
    {
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
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_POST['http_server'] = $this->http;
        ob_start();
        try {
            think\Container::get('app', [APP_PATH])
                           ->run()
                           ->send();
        } catch (Exception $e) {

        }
        $result = ob_get_contents();
        ob_end_clean();
        $response->end($result);
    }

    public function onTask($serv, $taskId, $workId, $data)
    {

        $task   = new \app\common\lib\Task();
        $result = call_user_func([$task, $data['method']], $data['data']);

        return $result;
        //这里是异步运行的, 运行可知,
        //这里的var_dump在连接后直接输出,而3秒后才输出onFinish中的内容
        var_dump($data);
        //耗时3S
        sleep(3);

        return "task任务结束"; //这里return 的数据在finish中可以接收的到
    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "taskID: {$taskId}\n";
        echo "finish接收到的数据: {$data}";  //这个是task完成会return 上来的数据,部署传给task的数据
    }

    public function onClose($ws, $fd)
    {
        echo "clientId{$fd}\n";
    }
}

$http = new http();
