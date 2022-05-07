<?php

namespace addons\cms\controller;

use addons\third\model\Third;
use app\admin\model\WechatpushLog;
use addons\cms\model\Wechataccounts;
use think\Db;


class Wechatpush extends Base
{
    protected $noNeedLogin = ['*'];
    public function push($userid, $template_id, $data, $event = null) //pushwecat?
    // public function push()
    {
        $userOpenid = $this->useridToopenid($userid);
        $template_id = $template_id; //'tHRoi4rIN9k1REWATqWZdgzbtalqPSuXrGTFN_SWAnY';

        $data = [
            'touser' => $userOpenid,
            'template_id' =>  $template_id, //些处为公众平台添加的模板ID
            // 'url' => $Gourl,
            'topcolor' => "#FF0000",
            'data' => $data,
            "miniprogram" => [
                "appid" => "小程序id",
                "pagepath" => "pages/special/special"
            ],
            // 'data' => [
            //         'first' => ['value' => "副标题内容", 'color' => "#fc0101"], //类似副标题
            //         'keyword1' => ['value' => "1", 'color' => "#ccc"], //标题
            //         'keyword2' => ['value' => "1", 'color' => "#ccc"], //内容
            //         'keyword3' => ['value' => "1", 'color' => "#ccc"], //内容
            //         'remark' => ['value' => "1"]  //内容
            // ],
        ];
       $get_all_access_token = $this->get_access_token(); //获取公众号信息token
        $json_data = json_encode($data); //转化成json数组让微信可以接收
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $get_all_access_token; //模板消息请求URL
        $res = $this->curl_post($url, urldecode($json_data));
        $res = json_decode($res, true);

        // if ($res['errcode'] == 0 && $res['errmsg'] == "ok") {
        //     WechatpushLog::create([
        //         'user_id' => $userid,
        //         'pushtime' => time(),
        //         'pushdata' => json_encode($data['data'],JSON_UNESCAPED_UNICODE),
        //         'event' => $event,
        //         'push_params' => '0',
        //         'errmsg' => $res['errmsg'],
        //     ]);
        //     return json(['st' => 1, 'msg' => '发送成功']);
        // } else {
        //     WechatpushLog::create([
        //         'user_id' => $userid,
        //         'pushtime' => time(),
        //         'pushdata' => json_encode($data['data'],JSON_UNESCAPED_UNICODE),
        //         'event' => $event,
        //         'push_params' => '2',
        //         'errmsg' => $res['errmsg'],
        //     ]);
        //     return json(['st' => 0, 'msg' => '发送失败']);
        // }
    }
    public function useridToopenid($userid)
    {
        $a = Third::where('user_id', $userid)->find();
        $b = Wechataccounts::where('unionid', $a['unionid'])->find();
        return $b['openid'];
    }
    //保存微信access_token到数据库缓存
    public function get_access_token()
    {
        $config = get_addon_config('suisunwechat');
        $appid = $config['app_id'];
        $secret = $config['secret'];
        $cachedata =  Db::name('access_token')
            ->where('id', 1874)
            ->where('time', '>', time())
            ->find();
        if ($cachedata) {
            return $cachedata['access_token'];
        } else {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
            $res = $this->curl_get($url);
            $res = json_decode($res, true);
            $time = time() + 6999;
            $data = [
                'access_token' => $res['access_token'],
                'time' => $time,
            ];
            Db::name('access_token')->where('id', 1874)->update($data);
            return $res['access_token'];
        }
    }

    /**
     * 获取全部关注公众号的用户
     */
    public function getOpenId()
    {
        $config = get_addon_config('suisunwechat');
        $appid = $config['app_id'];
        $secret = $config['secret'];

        $res = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret);
        $resdata = json_decode($res, true);
        $access_token = $resdata['access_token'];

        $res1 = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=" . $access_token);
        $resdata1 = json_decode($res1, true);
        $openidList = $resdata1['data']['openid'];

        return $openidList;
    }
}
