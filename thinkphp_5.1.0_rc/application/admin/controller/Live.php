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
            Util::responseJson('', '参数不存在');
        }
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            2 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];
        $data  = [
            'method' => 'pushLive',
            'data'   => [
                'type'    => empty($_GET['type']) ? '' : intval($_GET['type']),
                'title'   => empty($teams[$_GET['team_id']]['name']) ? '直播员' : $teams[$_GET['team_id']]['name'],
                'logo'    => empty($teams[$_GET['team_id']]['logo']) ? '' : $teams[$_GET['team_id']]['logo'],
                'content' => empty($_GET['content']) ? '' : $_GET['content'],
                'image'   => empty($_GET['image']) ? '' : $_GET['image'],
                'date'    => date('Y-m-d H:i:s', time()),
            ],
        ];
        $_POST['http_server']->task($data);
        Util::responseJson('1', '推送成功', '');
    }
}