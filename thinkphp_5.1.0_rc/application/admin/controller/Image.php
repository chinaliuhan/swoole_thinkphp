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
class Image
{

    public function index()
    {
        $file       = request()->file('file');
        $uploadInfo = $file->move('../public/static/upload');
        if ($uploadInfo) {
            $data = [
                'image' => config('live.host') . '/upload/' . $uploadInfo->getSaveName(),
            ];

            return Util::responseJson('0', '', $data);
        }

        return Util::responseJson('1', '系统繁忙');
    }
}