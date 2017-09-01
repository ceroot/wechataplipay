<?php
/**
 * Created by PhpStorm.
 * User: liushizhao
 * Date: 2017/8/31
 * Time: 下午2:26
 */

namespace Home\Controller;


use Think\Controller;

class WxLoginController extends Controller
{
    /**
     * pc 网站扫码登录
     */
    public function pc()
    {
        $appid = C("wx.pc_appid");
        $secret = C("wx.pc_secret");
        $http = "http://wwww.demo.com/index.php/Home/WxLogin/callBackWxPc";
        $url = urlencode($http);
        $ck = '12345678900987654321123456789001';//可以设置随机数或字母数字
        session("check", $ck);
        $url = "https://open.weixin.qq.com/connect/qrconnect?appid={$appid}&redirect_uri={$url}&response_type=code&scope=snsapi_login&state={$ck}#wechat_redirect";

        header("Location:" . $url);
    }

    public function callBackWxPc()
    {
        $appid = C("wx.pc_appid");
        $secret = C("wx.pc_secret");
        $data = I("get.");
        if ($data['code']) {
            $code = $data["code"];
            $state = $data["state"];
            $ck = session("check");
            if ($state == $ck && $code != "") {
                $get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
                $wx_info = $this->HttpGet($get_token_url);
                if (is_array($wx_info)){
                    $access_token = $wx_info['access_token'];
                    $openid = $wx_info['openid'];
                    session("check", null);
                    $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
                    $wx_user_info = $this->HttpGet($get_user_info_url);
                    var_dump($wx_user_info);
                    //TODO

                } else {
                    $this->error("授权失败,或改用其他方式登录");
                }


            }
        }
    }

    private function HttpGet($url)
    {
        //启动curl
        $curl = curl_init();
        //设置参数
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //访问地址
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error' . curl_error($curl);
        }
//关闭链接
        curl_close($curl);
        return json_decode($res, true);
    }


    /**
     * 微信登录 公众号内登录
     */
    public function mp()
    {
        $appid = C("wx.mp_appid");
        $secret = C("wx.mp_secret");

        $web_url = urlencode('http://www.demo.cn/index.php/Home/WxLogin/callBackMp');
        $ck = '12345678900987654321123456789001';//可以设置随机数或字母数字
        session("check", $ck);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$web_url}&response_type=code&scope=snsapi_userinfo&state={$ck}#wechat_redirect";
//        dd($url);
        header("Location:" . $url);

    }

    public function callBackMp()
    {
        $data = I('get.');
        $this->code=$data['code'];
        $this->state=$data['state'];
        $this->_title="微信登录";
        $this->display();
    }
    public function wxSub()
    {
        $appid = C("wx.mp_appid");
        $secret = C("wx.mp_secret");
        $data = I('post.');
        if ($data['code']) {
            $code = $data['code'];
            $check = session('w_check');
            $state = $data['state'];
            if ($state == $check && $code != '') {
                session('check', null);
                $get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
                $wx_info = carHttpGet($get_token_url);
                if (is_array($wx_info)) {
                    $openId = $wx_info['openid'];
                    if (is_array($wx_info)){
                        $access_token = $wx_info['access_token'];
                        $openid = $wx_info['openid'];
                        session("check", null);
                        $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
                        $wx_user_info = $this->HttpGet($get_user_info_url);
                        var_dump($wx_user_info);
                        //TODO

                    } else {
                        $this->error("授权失败,或改用其他方式登录");
                    }


                }
            }
        }
    }


}