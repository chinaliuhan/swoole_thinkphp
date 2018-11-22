<?php

/**
 * @Author: liuhao
 * @Date  :   2018/11/22
 * @Time  :   19:19
 * @File  :   php.php
 * @Desc  : ...
 */
class  Server
{

    const POST = 8811;

    /**
     * Method  port
     *
     * @desc    端口监控
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/11/22
     * @time    19:26
     * @return  void
     */
    public function port()
    {
        $shell  = 'netstat -anp 2>/dev/null | grep ' . self::POST . ' | grep LISTEN | wc -l';
        $result = shell_exec($shell);
        if ($result == 1) {
            echo '进程存活中: ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
        }
        echo '进程不存在: ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }
}

//2秒一次进程的端口监控
swoole_timer_tick('2000', function ($timerId) {
    (new Server())->port();
    echo 'time_start' . PHP_EOL;
});
