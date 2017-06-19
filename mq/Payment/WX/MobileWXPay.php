<?php

ini_set('date.timezone', 'Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "./lib/WxPay.Api.php";
require_once "./lib/WxPay.JsApiPay.php";
require_once './lib/log.php';

//初始化日志
$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);
//打印输出数组信息
function printf_info($data) {
	foreach ($data as $key => $value) {
		echo "<font color='#00ff55;'>$key</font> : $value <br/>";
	}
}

//session_start();
$orderid = $_GET['orderid'];
$money = $_GET['money'];
$conponid = $_GET['couponid'];
//①、获取用户openid
//$tools = new JsApiPay();
//$openId = $tools -> GetOpenid();
//②、统一下单
$input = new WxPayUnifiedOrder();
$input -> SetBody("课程订单号:".$orderid);
$input -> SetAttach("购买有帮课程");
$input -> SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
$input -> SetTotal_fee(strval(floatval($money * 100)));
$input -> SetTime_start(date("YmdHis"));
$input -> SetTime_expire(date("YmdHis", time() + 600));
$input -> SetGoods_tag("购买有帮课程");
$input -> SetNotify_url("./lib/notify.php");
$input -> SetTrade_type("APP");
//$input -> SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
if (is_array($order)) {
    echo json_encode($order);
}
?>