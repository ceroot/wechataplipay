<?php
return array(
	//'配置项'=>'配置值'
    'alipay_config' => array(
        'partner' => ' ',   //这里是你在成功申请支付宝接口后获取到的PID；
        'key' => ' ',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type' => strtoupper('MD5'),
        'input_charset' => strtolower('utf-8'),
        'cacert' => getcwd() . '\\cacert.pem',
        'transport' => 'http',
    ),
    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；

    'alipay' => array(
        //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
        'seller_email' => 'XXXXXX@qq.com',
        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url' => 'http://www.demo.cn/index.php/Home/Alipay/returnurl',
        //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage' => 'Order/myOrder',
        //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage' => 'Order/myOrder',
    ),

    "wx"=>array(
        //pc
        //开放平台 中添加应用，获取appid和secret
        "pc_appid"=>'',
        "pc_secret"=>'',
        //公众号内微信登陆
        "mp_appid"=>'',
        "mp_secret"=>'',

    )
);