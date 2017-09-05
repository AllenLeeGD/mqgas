<?php
header("Content-type: text/html; charset=utf-8"); 
include_once("WxPayPubHelper.php");
//使用native通知接口
$unifiedOrder = new UnifiedOrder_pub();
$orderid = $_GET['orderid'];
$totalfee = $_GET['totalfee'];
$unifiedOrder->setParameter("body","订单号:".$orderid);//商品描述
//自定义订单号，此处仅作举例
$timeStamp = time();
$out_trade_no = $orderid."_$timeStamp";
$unifiedOrder->setParameter("spbill_create_ip","120.77.221.110");
$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号 
$unifiedOrder->setParameter("total_fee",$totalfee);//总金额
$unifiedOrder->setParameter("notify_url", "http://newoceangas.cn/Payment/WX/lib/notifymobile.php");//通知地址 
$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
//获取统一支付接口结果
$unifiedOrderResult = $unifiedOrder->getResult();

//商户根据实际情况设置相应的处理流程
if ($unifiedOrderResult["return_code"] == "FAIL") 
{
    //商户自行增加处理流程
    echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
}
elseif($unifiedOrderResult["result_code"] == "FAIL")
{
    //商户自行增加处理流程
    echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
    echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
}
else if($unifiedOrderResult["code_url"] != NULL)
{
    //从统一支付接口获取到code_url
    $code_url = $unifiedOrderResult["code_url"];
    //商户自行增加处理流程
    //......
}
//$this->assign('out_trade_no',$out_trade_no);
//$this->assign('code_url',$code_url);
//$this->assign('unifiedOrderResult',$unifiedOrderResult);

echo $code_url;
