<?php

namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Sms;
use app\common\lib\Redis;

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
        $code = mt_rand(1000, 9999);
        //发送短信
        if (!Sms::sendSms($phoneNum, $code)) {
            return Util::responseJson('', '验证码发送失败,请稍后重试');
        }
        //Redis存储, 异步Redis
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect('localhost');
        $setSms = $redis->set(Redis::smsKey($phoneNum), $code, 100);
        if (!$setSms) {
            return Util::responseJson('', '验证码存储失败,请稍后重试');
        }

        return Util::responseJson('', '发送成功');
    }
}