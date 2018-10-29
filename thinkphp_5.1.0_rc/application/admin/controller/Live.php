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

    /**
     * Method  push
     *
     * @desc    后台对前端用户的推送
     * @author  liuhao <lh@btctrade.com>
     * @date    2018/10/29
     * @time    14:39
     * @throws \Exception
     * @return  void
     */
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
            'type'    => empty($_GET['type']) ? '' : intval($_GET['type']),
            'title'   => empty($teams[$_GET['team_id']]['name']) ? '直播员' : $teams[$_GET['team_id']]['name'],
            'logo'    => empty($teams[$_GET['team_id']]['logo']) ? '' : $teams[$_GET['team_id']]['logo'],
            'content' => empty($_GET['content']) ? '' : $_GET['content'],
            'image'   => empty($_GET['image']) ? '' : $_GET['image'],
            'date'    => date('Y-m-d H:i:s', time()),
        ];
        $clients = \app\common\lib\Predis::getInstance()->sMembers(config('redis.live_redis_key'));
        foreach ($clients as $fd) {
            //删除失效的连接
            $fdExist = $_POST['http_server']->exist($fd);
            if (!$fdExist) {
                \app\common\lib\Predis::getInstance()->sRem(config('redis.live_redis_key'), $fd);
                continue;
            }
            //对有效连接推送消息
            $_POST['http_server']->push($fd, json_encode($data));
        }
        Util::responseJson('', '推送成功', '');
    }
}