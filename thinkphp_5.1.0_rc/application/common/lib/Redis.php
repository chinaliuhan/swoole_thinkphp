<?php

namespace app\common\lib;

/**
 * @Author: liuhao
 * @Date  :   2018/10/12
 * @Time  :   00:30
 * @File  :   Redis.php
 * @Desc  : ...
 */
class  Redis
{

    protected static $smsPre = 'sms_';

    protected static $userPre = 'user_';

    public static function smsKey($phone)
    {
        return self::$smsPre . $phone;
    }

    public static function userKey($phone)
    {
        return self::$userPre . $phone;
    }
}