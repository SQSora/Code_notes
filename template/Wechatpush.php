<?php

namespace addons\cms\controller;

use addons\third\model\Third;
use app\admin\model\WechatpushLog;
use addons\cms\model\Wechataccounts;
use think\Db;


class Wechatpush
{
    public function push($userid, $template_id, $data, $event = null)
    {   
        $data = [   //公众号模板的数据
            'first' => ['value' => "副标题内容", 'color' => "#fc0101"], //类似副标题
            'keyword1' => ['value' => 'name'],
            'keyword2' => ['value' =>'哈哈'],
            'remark' => ['value' =>'备注']
        ];
        
        $pushData = [
            'touser'       => '目标用户的openid',
            'template_id'  => $template_id,        //'tHRoi4rIN9k1R...'//在公众平台模板裤的模板ID
            // 'url' => $Gourl,                     //模板消息里“查看详情”的链接
            'topcolor'     => "#FF0000",            //顶部颜色,可不写.$data类同
            'data'         => $data,                //模板消息里的变量替换内容
            "miniprogram"  => [
                "appid"    => "小程序的appid",//给模板消息绑定小程序
                "pagepath" => "小程序的页面路径"
            ],
        ];
        
        $access_token = $this->get_access_token(); //获取公众号token
        $json_data = json_encode($pushData); //转化成json数组让微信可以接收
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token; //模板消息请求URL
        $res = $this->curl_post($url, urldecode($json_data));
        $res = json_decode($res, true);
        if ($res['errcode'] == 0 && $res['errmsg'] == "ok") {
            //发送成功后操作
        } else {
            //发送失败后操作
        }
    }
    
    //将userid转为openid
    public function useridToOpenid($userid)
    {
        $a = Third::where('user_id', $userid)->find();
        $b = Wechataccounts::where('unionid', $a['unionid'])->find();
        return $b['openid'];
    }
    
    //将Wechat access_token 保存到数据库中缓存
    public function get_access_token()
    {
        $appid = 'appid';
        $secret = 'AppSecret';
        $cachedata =  Db::name('access_token')
            ->where('id', 1874)
            ->where('time', '>', time())
            ->find();
        if ($cachedata) {
            return $cachedata['access_token'];
        } else {
            //数据库token过期，重新到微信获取
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
            $res = $this->curl_get($url);
            $res = json_decode($res, true);
            $time = time() + 6999;  //微信默认7200秒过期
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
/**
 * 封装POST请求
 * @param string $url 请求地址
 * @param string $data 请求参数
 * @param string string $cookiePth
 */
function curl_post($url, $data = [], $cookiePath = '')
{
    $ch = curl_init(); // 初始化
    curl_setopt($ch, CURLOPT_URL, $url); // 抓取指定网页
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1); // POST提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 请求参数
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath); // 连接结束后保存cookie信息的文件
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath); // 包含cookie信息的文件
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 禁用后cURL将终止从服务端进行验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查服务器SSL证书中是否存在一个公用名
    $res = curl_exec($ch); // 执行一个cURL会话
    curl_close($ch); // 关闭一个cURL会话
    return $res;
}
/**
 * 封装GET请求
 * @param string $url 请求地址
 * @param array $data 请求参数
 * @param string $cookiePath 请求所保存的cookie路径
 */
function curl_get($url, $data = [], $cookiePath = '')
{
    if (count($data) != 0) {
        $url = get_query_url($url, $data);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
}
