<?php
/**
 * Created by PhpStorm.
 * User: liushizhao
 * Date: 2017/8/30
 * Time: 下午4:53
 */

namespace Home\Controller;


use Think\Controller;

class WxpayController extends Controller
{

    /**
     * 公众号内支付
     * 注意公众号内支付：在商户平台中 产品中心-》开发配置-》公众号支付设置
     * 支付授权目录 对于thinkphp 授权目录为："http://www.demo.cn/index.php/Home/Wx/"
     * 若有其他参数则应为： "http://www.demo.cn/index.php/Home/Wx/orderId/"
     *
     */
    public function mobileMpPay()
    {
        Vendor('Wxpay.WxPayApiPay');
        Vendor('Wxpay.WxPayJsApiPay');
        $orderId="wx".date("YmdHis");//订单号
        $total_fee = 1; //微信是以分为单位
        $notify_url = '';//异步通知地址
        $input=new \WxPayUnifiedOrder();
        $tools=new \JsApiPay();
        $openid=$tools->GetOpenid();
        $detail = "订单详情";
        $input->setBody($detail);
        $input->SetAttach($detail);
        $input->SetOut_trade_no($orderId);//
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis", NOW_TIME));
        $input->SetTime_expire(date("YmdHis", NOW_TIME + 50));
        $input->SetGoods_tag($detail);
        $input->SetNotify_url("http://www.demo.cn/index.php/Home/Wx/mobileMpNotifyUrl");
        $input->SetProduct_id($orderId);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order=\WxPayApi::unifiedOrder($input);
        $jsApiParameters=$tools->GetJsApiParameters($order);
        $this->assign('jsApiParameters', $jsApiParameters);
        $this->assign('totalPrice',$total_fee);
        $this->assign("orderId",$orderId);
        $this->assign("body",$detail);
        $this->display();
    }

    /**
     * @param $data
     * @param $msg
     * 公众号异步通知地址
     */
    public function mobileMpNotifyUrl($data,&$msg)
    {
        Vendor('Wxpay.WxPayApiPay');
        Vendor('Wxpay.WxPayNotify');

//        $notifyOutPut=array();
//        if(!array_key_exists('transaction_id')){
//            $msg="参数错误";
//            return false;
//        }
//        if($this->QueryOrder($data['transaction_id'])){
//
//        }
        $xmldata=file_get_contents("php://input");
        libxml_disable_entity_loader(true);
        $data=json_encode(json_encode(simplexml_load_string($xmldata,'SimpleXMLElement',LIBXML_NOCDATA)),true);
        if($data){
            if($data['return_code']=="SUCCESS"){
                // TODO
            }else{
                //
                echo "支付失败";
            }
        }else{
            file_put_contents("zhifu.txt",$xmldata,FILE_APPEND);
        }
    }


    /**
     * pc 扫码支付
     */
    public function wxPcPay()
    {
        vendor("Wxpay.WxPayData");
        vendor("Wxpay.WxPayNativePay");
        $orderId='';//订单号
        $total_fee = 1;
        $notify_url = 'http://www.demo.cn/index.php/Home/Wx/pcNotifyUrl';//异步通知地址
        $input = new \WxPayUnifiedOrder();
        $notify = new \NativePay();
        $detail = "订单详情";
        $input->setBody($detail);
        $input->SetAttach($detail);
        $input->SetOut_trade_no($orderId);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start(date("YmdHis", NOW_TIME));
        $input->SetTime_expire(date("YmdHis", NOW_TIME + 50));
        $input->SetGoods_tag($detail);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($orderId);
        $result = $notify->GetPayUrl($input);
        if (IS_AJAX) {
            if ($result["return_code"] == "FAIL") {
                //商户自行增加处理流程
                $this->ajaxReturn(array("state" => 0, 'msg' => "通信出错：" . $result['return_msg']));

            } elseif ($result["result_code"] == "FAIL") {
                //商户自行增加处理流程
                $this->ajaxReturn(array("state" => 0, 'msg' => "错误代码：" . $result['err_code'] . "错误代码描述：" . $result['err_code_des']));

            } elseif ($result["result_code"] == "SUCCESS" && $result["return_code"] == "SUCCESS") {
                $this->ajaxReturn(array('state' => 1, "code_url" => $result["code_url"]));
            }
        }
        if ($result["return_code"] == "FAIL") {
            //商户自行增加处理流程
            echo "通信出错：" . $result['return_msg'] . "<br>";
        } elseif ($result["result_code"] == "FAIL") {
            //商户自行增加处理流程
            echo "错误代码：" . $result['err_code'] . "<br>";
            echo "错误代码描述：" . $result['err_code_des'] . "<br>";
        } elseif ($result["result_code"] == "SUCCESS" && $result["return_code"] == "SUCCESS") {
            //从统一支付接口获取到code_url
            $code_url = $result["code_url"];
            $this->assign('out_trade_no', $orderId);
            $this->assign("orderId",$orderId);
            $this->assign('code_url', $code_url);
            $this->assign('totalPrice', $total_fee);
            $this->assign('unifiedOrderResult', $result);
            session("i", 30);
            $this->assign("detail", $detail);
            $this->display();
        }
    }
    /**
     * pc
     * 查询订单 异步请求订单状态
     */
    public function returnFun()
    {
        vendor("Wxpay.WxPayNotify");
        vendor("Wxpay.WxPayApi");
        $out_trade_no = $_GET["out_trade_no"];
        //使用订单查询接口
        $input = new \WxPayOrderQuery();
        $input->SetOut_trade_no($out_trade_no);
        $resault = \WxPayApi::orderQuery($input);
        if ($resault["return_code"] == "FAIL") {
            $this->ajaxReturn(array("state" => 0, "msg" => "支付失败"));
        } elseif ($resault["result_code"] == "FAIL") {
            $this->ajaxReturn(array("state" => 0, "msg" => "支付失败"));
//                $this->error($out_trade_no);
        } else {
            $i = session("i");
            $i--;
            session("i", $i);
            //判断交易状态
            switch ($resault["trade_state"]) {
                case SUCCESS:
                    $this->ajaxReturn(array("state" => 1, "msg" => "订单支付成功" . $resault["trade_state"], "i" => session("i")));
                    // $this->success("支付成功！");
                    break;
                case REFUND:
                    $this->ajaxReturn(array("state" => 2, "msg" => "超时关闭订单" . $resault["trade_state"], "i" => session("i")));
                    break;
                case NOTPAY:
                    $this->ajaxReturn(array("state" => 3, "msg" => "超时关闭订单" . $resault["trade_state"], "i" => session("i")));
                    break;
                case CLOSED:
                    $this->ajaxReturn(array("state" => 4, "msg" => "超时关闭订单" . $resault["trade_state"], "i" => session("i")));
                    break;
                case PAYERROR:
                    $this->ajaxReturn(array("state" => 5, "msg" => "支付失败" . $resault["trade_state"], "i" => session("i")));
                    break;
                default:
                    $this->ajaxReturn(array("state" => 6, "msg" => "未知错误" . $resault["trade_state"], "i" => session("i")));
                    break;
            }
        }

    }

    /**
     * @param $data
     * @param $msg
     * pc
     * 异步通知地址
     */
    public function  pcNotifyUrl($data,&$msg)
    {
        Vendor('Wxpay.WxPayApiPay');
        Vendor('Wxpay.WxPayNotify');

//        $notifyOutPut=array();
//        if(!array_key_exists('transaction_id')){
//            $msg="参数错误";
//            return false;
//        }
//        if($this->QueryOrder($data['transaction_id'])){
//
//        }
        $xmldata=file_get_contents("php://input");
        libxml_disable_entity_loader(true);
        $data=json_encode(json_encode(simplexml_load_string($xmldata,'SimpleXMLElement',LIBXML_NOCDATA)),true);
        if($data){
            if($data['return_code']=="SUCCESS"){
                // TODO
            }else{
                //
                echo "支付失败";
            }
        }else{
            file_put_contents("pczhifu.txt",$xmldata,FILE_APPEND);
        }
    }




    //查询订单

}