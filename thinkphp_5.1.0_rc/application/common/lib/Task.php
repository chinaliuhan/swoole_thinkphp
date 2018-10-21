<?php

namespace app\common\lib;

/**
 * @Author: liuhao
 * @Date  :   2018/10/21
 * @Time  :   19:25
 * @File  :   Task.php
 * @Desc  : ...
 */
class Task
{


    public function sendSms($data)
    {
        $result = Sms::sendSms($data['phone'], $data['code']);
        if (!$result) {
            return false;
        }
        $setRedis = Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code']);
        if (empty($setRedis)) {
            return false;
        }

        return true;
    }
}