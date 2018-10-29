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


    public function sendSms($data, $server)
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

    public function pushLive($data, $server)
    {
        $clients = Predis::getInstance()->sMembers(config('redis.live_redis_key'));
        foreach ($clients as $fd) {
            //删除失效的连接
            $fdExist = $server->exist($fd);
            if (!$fdExist) {
                Predis::getInstance()->sRem(config('redis.live_redis_key'), $fd);
                continue;
            }
            //对有效连接推送消息
            $server->push($fd, json_encode($data));
        }

        return true;

    }
}