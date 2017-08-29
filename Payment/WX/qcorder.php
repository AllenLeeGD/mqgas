<?php
include_once("WxPayPubHelper.php");
//使用native通知接口
$nativeCall = new NativeCall_pub();

//接收微信请求
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$nativeCall -> saveData($xml);

if ($nativeCall -> checkSign() == FALSE) {
	$nativeCall -> setReturnParameter("return_code", "FAIL");
	//返回状态码
	$nativeCall -> setReturnParameter("return_msg", "签名失败");
	//返回信息
} else {
	//提取product_id
	$product_id = $nativeCall -> getProductId();
	//分离orderid和totalfee
//	$orderid = explode("-", $product_id)[0];
//	$totalfee = explode("-", $product_id)[1];

	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();

	//与native_call_qrcode.php中的静态链接二维码对应
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder -> setParameter("body", "new");
	//商品描述
	//自定义订单号，此处仅作举例
	$timeStamp = time();
	$unifiedOrder -> setParameter("out_trade_no", "123456");
	//商户订单号             $unifiedOrder->setParameter("product_id","$product_id");//商品ID
	$unifiedOrder -> setParameter("total_fee", "1");
	//总金额
	$unifiedOrder -> setParameter("notify_url", "http://newoceangas.cn/Payment/WX/lib/notify.php");
	//通知地址
	$unifiedOrder -> setParameter("trade_type", "NATIVE");
	//交易类型
//	$unifiedOrder -> setParameter("product_id", $product_id);
$unifiedOrder -> setParameter("product_id", "123456");
	//用户标识
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	
	//获取prepay_id
	$prepay_id = $unifiedOrder -> getPrepayId();
	//设置返回码
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$nativeCall -> setReturnParameter("return_code", "SUCCESS");
	//返回状态码
	$nativeCall -> setReturnParameter("result_code", "SUCCESS");
	//业务结果
	$nativeCall -> setReturnParameter("prepay_id", $prepay_id);
	//预支付ID
		
}

//将结果返回微信
$returnXml = $nativeCall -> returnXml();
//log_result($log_name, "【返回微信的native响应】:\n" . $returnXml . "\n");
echo $returnXml;
