<?php

namespace app\common\lib;

/**
 * @Author: liuhao
 * @Date  :   2018/10/12
 * @Time  :   00:30
 * @File  :   Predis.php
 * @Desc  : ...
 */
class  Predis
{

    private $redis;
    private static $instance;


    /**
     * Method  getInstance
     *
     * @desc    单例
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/20
     * @time    02:10
     * @static
     * @throws \Exception
     * @return  \app\common\lib\Predis
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Redis constructor.
     *
     * @author  liuhao
     * @date    2018/10/20
     * @time    01:48
     * @throws \Exception
     */
    private function __construct()
    {
        $this->redis = new \Redis();
        $result      = $this->redis->connect(config('redis.host'), config('redis.port'), config('reids.timeOut'));
        if ($result === false) {
            throw  new \Exception('Redis连接失败');
        }
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $timeOut = 0)
    {
        if ($timeOut <= 0) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $timeOut, $value);
    }

}