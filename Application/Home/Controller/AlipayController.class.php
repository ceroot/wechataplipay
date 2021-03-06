<?php
/**
 * Created by PhpStorm.
 * User: liushizhao
 * Date: 2017/8/30
 * Time: 下午4:52
 */

namespace Home\Controller;


use Think\Controller;

class AlipayController extends Controller
{

    public function _initizlize()
    {
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }

    /**
     * pc 端支付
     */
    public function pcPayment()
    {
        $out_trade_no = '';//订单号
        /*********************************************************
         * 把alipayapi.php中复制过来的如下两段代码去掉，
         * 第一段是引入配置项，
         * 第二段是引入submit.class.php这个类。
         * 为什么要去掉？？
         * 第一，配置项的内容已经在项目的Config.php文件中进行了配置，我们只需用C函数进行调用即可；
         * 第二，这里调用的submit.class.php类库我们已经在PayAction的_initialize()中已经引入；所以这里不再需要；
         *****************************************************/
        header("Content-type:text/html;charset=utf-8");//2017年7月28日10:02:30，这个必须交上不然会出现 ILLEGAL_SIGN 错误（不能有乱码）
        $alipay_config = C('alipay_config');
        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
        $return_url = C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填
        //商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $total_fee = "0.01";   //付款金额  //必填 通过支付页面的表单进行传递
        $show_url = "";  //商品展示地址 通过支付页面的表单进行传递
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $anti_phishing_key = $alipaySubmit->query_timestamp();//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            'logistics_type' => 'EXPRESS',
            'logistics_fee' => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            "partner" => trim($alipay_config['partner']),
            "payment_type" => $payment_type,
            "notify_url" => "http://www.demo.com/index.php/Home/Alipay/notifyUrl",
            "return_url" => "http://www.demo.com/index.php/Home/Alipay/returnUrl",
            "seller_email" => $seller_email,
            "out_trade_no" => $out_trade_no,
            // "subject" => $body['orderParams']['subject'],
            "subject" => '订单号：' . $out_trade_no,
            "total_fee" => $total_fee,
            //"body" => $body['orderParams']['body'],
            "body" => "订单详情：XXXXX",
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipay_config['input_charset']))
        );
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "确认");
        echo $html_text;
    }

    /**
     * 手机端支付
     */
    public function mobilePayment()
    {
        $out_trade_no = '';//订单号
        /*********************************************************
         * 把alipayapi.php中复制过来的如下两段代码去掉，
         * 第一段是引入配置项，
         * 第二段是引入submit.class.php这个类。
         * 为什么要去掉？？
         * 第一，配置项的内容已经在项目的Config.php文件中进行了配置，我们只需用C函数进行调用即可；
         * 第二，这里调用的submit.class.php类库我们已经在PayAction的_initialize()中已经引入；所以这里不再需要；
         *****************************************************/
        header("Content-type:text/html;charset=utf-8");//2017年7月28日10:02:30，这个必须交上不然会出现 ILLEGAL_SIGN 错误（不能有乱码）
        $alipay_config = C('alipay_config');
        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
        $return_url = C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填
        //商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $total_fee = "0.01";   //付款金额  //必填 通过支付页面的表单进行传递
        $show_url = "";  //商品展示地址 通过支付页面的表单进行传递
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $anti_phishing_key = $alipaySubmit->query_timestamp();//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "payment_type" => $payment_type,
            "notify_url" => "http://www.demo.com/index.php/Home/Alipay/notifyUrl",
            "return_url" => "http://www.demo.com/index.php/Home/Alipay/returnUrl",
            "seller_email" => $seller_email,
            "out_trade_no" => $out_trade_no,
            "subject" => '订单号：' . $out_trade_no,
            "total_fee" => $total_fee,
            "body" => "订单详情：XXXXX",
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipay_config['input_charset']))
        );
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "确认");
        echo $html_text;
    }

    /**
     * 返回地址
     */
    public function returnUrl()
    {
        //这里还是通过C函数来读取配置项，赋值给$alipay_config

        $alipay_config = C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new  \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $out_trade_no = $_POST['out_trade_no'];      //商户订单号
            $trade_no = $_POST['trade_no'];          //支付宝交易号
            $trade_status = $_POST['trade_status'];      //交易状态
            $total_fee = $_POST['total_fee'];         //交易金额
            $notify_id = $_POST['notify_id'];         //通知校验ID。
            $notify_time = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            $buyer_email = $_POST['buyer_email'];       //买家支付宝帐号；
            $parameter = array(
                "out_trade_no" => $out_trade_no, //商户订单编号；
                "trade_no" => $trade_no,     //支付宝交易号；
                "total_fee" => $total_fee,    //交易金额；
                "trade_status" => $trade_status, //交易状态
                "notify_id" => $notify_id,    //通知校验ID。
                "notify_time" => $notify_time,  //通知的发送时间。
                "buyer_email" => $buyer_email,  //买家支付宝帐号；
            );
            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                // TODO
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //TODO
            }
            echo "success";        //请不要修改或删除
        } else {
            //验证失败
            echo "fail";
        }
    }

    /**
     * 异步通知地址
     */
    public function notifyUrl()
    {
        //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config = C('alipay_config');
        $alipayNotify = new  \ AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $out_trade_no = $_GET['out_trade_no'];      //商户订单号
            $trade_no = $_GET['trade_no'];          //支付宝交易号
            $trade_status = $_GET['trade_status'];      //交易状态
            $total_fee = $_GET['total_fee'];         //交易金额
            $notify_id = $_GET['notify_id'];         //通知校验ID。
            $notify_time = $_GET['notify_time'];       //通知的发送时间。
            $buyer_email = $_GET['buyer_email'];       //买家支付宝帐号；

            $parameter = array(
                "out_trade_no" => $out_trade_no,      //商户订单编号；
                "trade_no" => $trade_no,          //支付宝交易号；
                "total_fee" => $total_fee,         //交易金额；
                "trade_status" => $trade_status,      //交易状态
                "notify_id" => $notify_id,         //通知校验ID。
                "notify_time" => $notify_time,       //通知的发送时间。
                "buyer_email" => $buyer_email,       //买家支付宝帐号
            );
            //print_r($parameter);
            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //TODO
                $this->redirect(C('alipay.successpage'));//跳转到配置项中配置的支付成功页面；
            } else {
                //echo '2的这个地方啊';die;
                echo "trade_status=" . $_GET['trade_status'];
                $this->redirect(C('alipay.errorpage'));//跳转到配置项中配置的支付失败页面；
            }
        } else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "支付失败！";
        }
    }



}