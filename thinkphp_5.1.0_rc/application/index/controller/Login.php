<?php

namespace app\index\controller;

use app\common\lib\Predis;
use app\common\lib\Redis;
use app\common\lib\Util;
use Swoole\Http\Response;
use think\Exception;

/**
 * @Author: liuhao
 * @Date  :   2018/10/12
 * @Time  :   00:19
 * @File  :   Send.php
 * @Desc  : ...
 */
class Login
{

    public function index()
    {
        $phoneNum = intval($_GET['phone_num']);
        $code     = intval($_GET['code']);
        if (empty($phoneNum) || empty($code)) {
//            return Util::responseJson('', '参数不能为空');
        }
        try {
            $redisCode = Predis::getInstance()->get('sms_15011017921');
            if (empty($redisCode)) {
//                return Util::responseJson('', '请先获取手机验证码');
            }
        } catch (\Exception $e) {
            return Util::responseJson('', '系统繁忙稍后重试');
        }
        if ($code != $redisCode) {
//            return Util::responseJson('', '短信验证码错误');
        }
        $userData = [
            'user'      => $phoneNum,
            'secretKey' => md5(Redis::userKey($phoneNum)),
            'loginTime' => time(),
            'isLogin'   => 1,
        ];
        try {
            Predis::getInstance()->set(Redis::userKey($phoneNum), json_encode($userData), '1000');
        } catch (\Exception $e) {
            return Util::responseJson('', '系统错误请联系客服');
        }


        return Util::responseJson('', '登录成功', $userData);

    }

}