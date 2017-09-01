# 简介
以 thinkphp 为基础
 
## 支付宝支付
    核心代码：ThinkPHP->Library->Vendor->Alipay
    pc端支付  
        Application->Controller->Alipay.class.php->pcPayment
    移动端支付 
        Application->Controller->Alipay.class.php->mobilePayment
        
       ### 支付宝配置
       
       Application->Common->conf->config.php->alipay_config
       Application->Common->conf->config.php->alipay
   #### 新版支付核心代码 手机端
   ThinkPHP->Library->Vendor->AlipayMobile
   无pc端新版核心代码
        
    
## 微信支付  
    核心代码：ThinkPHP->Library->Vendor->Wxpay
    ### 微信支付配置
    ThinkPHP->Library->Vendor->Wxpay->WxConfig.php
    
    包含pc端扫码支付
        Application->Controller->WxpayController.class.php->wxPcpay
    公众号内部支付
        Application->Controller->WxpayController.class.php->mobileMpPay
        ps：公众号内部登录采用jsapi  需要配置appsecret  在公众平台获取
## 微信登录
 pc端微信登录
     Application->Controller->WxLoginController.class.php->pc
     ### 登录配置
     ##### 微信开放平台中申请 网站应用 获取
      Application->Common->conf->config.php->wx->pc_appid
      Application->Common->conf->config.php->wx->pc_secret
      
 公众号内部登录
    ### 登录配置
         ##### 微信公众平台  获取
          Application->Common->conf->config.php->wx->mp_appid
          Application->Common->conf->config.php->wx->mp_secret
    Application->Controller->WxLoginController.class.php->mp
    
