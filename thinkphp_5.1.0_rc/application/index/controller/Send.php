<?php

namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Sms;
use app\common\lib\Redis;
use think\Exception;

/**
 * @Author: liuhao
 * @Date  :   2018/10/12
 * @Time  :   00:19
 * @File  :   Send.php
 * @Desc  : ...
 */
class Send
{

    /**
     * Method  sms
     *
     * @desc    发送短信验证码
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/12
     * @time    00:24
     * @return  string
     */
    public function sms()
    {
        //todo
        // http_server中的GET参数被重新赋值为大写, 所以这里用小写拿不到数据,后面要看看老师是怎么做的
        //数据验证码
        $phoneNum = isset($_GET['phone_num']) ? intval($_GET['phone_num']) : 0;
        if (empty($phoneNum)) {
            return Util::responseJson('', '手机号码错误');
        }
        //生成随机数
        $code     = mt_rand(1000, 9999);
        $taskData = [
            'method' => 'sendSms',
            'data'   => [
                'phone' => $phoneNum,
                'code'  => $code,
            ],
        ];
        try {
            $_POST['http_server']->task($taskData);
        } catch (\Exception $e) {

            var_dump($e->getMessage());
        }

        return Util::responseJson('', '发送成功');
        // 上面采用了task任务这里弃用异步Redis
        //发送短信
        if (!Sms::sendSms($phoneNum, $code)) {
            return Util::responseJson('', '验证码发送失败,请稍后重试');
        }
        //Redis存储, 异步Redis
        $redis         = new \Swoole\Coroutine\Redis();
        $connectResult = $redis->connect(config('redis.host'), config('redis.port'), config('reids.timeOut'));
        if (!$connectResult) {
            return Util::responseJson('', 'Redis连接失败,请检查配置文件');
        }
        $setSms = $redis->set(Redis::smsKey($phoneNum), $code);
        if (!$setSms) {
            return Util::responseJson('', '验证码存储失败,请稍后重试');
        }

        return Util::responseJson('', '发送成功');
    }
}