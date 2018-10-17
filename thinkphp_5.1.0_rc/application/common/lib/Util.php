<?php

namespace app\common\lib;

/**
 * @Author: liuhao
 * @Date  :   2018/10/12
 * @Time  :   00:21
 * @File  :   Util.php
 * @Desc  : ...
 */
class Util
{

    /**
     * Method  responseJson
     *
     * @desc    ......
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/12
     * @time    00:24
     * @static
     *
     * @param        $status
     * @param string $message
     * @param array  $data
     *
     * @return  string
     */
    public static function responseJson($status, $message = '', $data = [])
    {
        $result = [
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ];
        echo json_encode($result);
    }

}