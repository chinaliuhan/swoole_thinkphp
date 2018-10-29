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

        if (empty($_GET)) {
            Util::responseJson('1', '参数不存在');
        }
        $teams   = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            2 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];
        $data    = [
            'type'    => intval($_GET['type']),
            'title'   => $teams[$_GET['team_id']]['name'] ?: '直播员',
            'logo'    => $teams[$_GET['team_id']]['logo'] ?: '',
            'content' => $_GET['content'] ?: '',
            'image'   => $_GET['image'] ?: '',
        ];
        $clients = \app\common\lib\Predis::getInstance()->sMembers(config('redis.live_redis_key'));
        foreach ($clients as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }
    }
}