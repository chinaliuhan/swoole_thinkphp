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

    protected static $pre = 'sms_';

    public static function smsKey($phone)
    {
        return self::$pre . $phone;
    }
}