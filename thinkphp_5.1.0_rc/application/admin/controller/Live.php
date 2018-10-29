<?php

namespace app\admin\controller;

use think\Exception;
use app\common\lib\Util;

/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 2018/10/22
 * Time: 5:40
 */
class Live
{

    public function push()
    {
        var_dump($_GET);
        $_POST['http_server']->push(2,'nihao');

    }
}