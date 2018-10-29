<?php

namespace app\index\controller;

use app\common\lib\Util;

/**
 * Class  Chart
 *
 * @package  app\index\controller
 * @author   liuhao <lh@btctrade.com>
 * @date     2018/10/29
 * @time     20:46
 */
class Chart
{

    /**
     * Method  index
     *
     * @desc    ......
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/29
     * @time    20:46
     * @return  void
     */
    public function index()
    {
        if (empty($_POST['content'])) {
            Util::responseJson('', '请输入内容', '');
        }
        $data = [
            'user'    => rand(0, 200),
            'content' => $_POST['content'],
        ];
        //遍历所有正在连接中的用户, 发送消息
        foreach ($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }
        Util::responseJson('1', 'success', '');

    }

}
