<?php
ini_set('display_errors',1);            //错误信息  
ini_set('display_startup_errors',1);    //php启动错误信息  
error_reporting(-1);     
header("Content-Type: text/html;charset=utf-8");
ini_set('date.timezone', 'Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "./Payment/WX/lib/WxPay.Api.php";
require_once "./Payment/WX/lib/WxPay.JsApiPay.php";
require_once './Payment/WX/lib/log.php';
//session_start();
//初始化日志
//$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
//$log = Log::Init($logHandler, 15);
//打印输出数组信息
//function printf_info($data) {
//	foreach ($data as $key => $value) {
//		echo "<font color='#00ff55;'>$key</font> : $value <br/>";
//	}
//}


$orderid = $_GET['orderid'];
$money = $_GET['money'];
$openid = $_GET['openid'];
//①、获取用户openid
$tools = new JsApiPay();
//$openId = $tools -> GetOpenid();
//echo $openId;
//sleep(1);
//②、统一下单
$input = new WxPayUnifiedOrder();
$input -> SetBody("微信支付金额:".$money);
$input -> SetAttach("微信支付");
//$input -> SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
$input -> SetOut_trade_no($orderid);
$input -> SetTotal_fee(intval($money * 100));
//$input -> SetTotal_fee("1");
$input -> SetTime_start(date("YmdHis"));
$input -> SetTime_expire(date("YmdHis", time() + 600));
$input -> SetGoods_tag("用户支付");
$input -> SetNotify_url("./lib/notify.php");
$input -> SetTrade_type("JSAPI");
$input -> SetOpenid($openid);
$order = WxPayApi::unifiedOrder($input);

$jsApiParameters = $tools -> GetJsApiParameters($order);
//echo $jsApiParameters;
//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>
			微信支付
		</title>
		<link href="../../css/mui.css" rel="stylesheet" />
		<link href="../../css/edu.css" rel="stylesheet" />
		<script src="../../js/mui.min.js"></script>
		<script src="../../js/util.js"></script>
		<script type="text/javascript">//调用微信JS api 支付
mui.ready(function() {
	mui.init({
		swipeBack: true, //启用右滑关闭功能
	});
});

function jsApiCall() {
	WeixinJSBridge.invoke(
			'getBrandWCPayRequest',<?php echo $jsApiParameters; ?>,
function(res) {
//	WeixinJSBridge.log(res.err_msg);
	if (res.err_msg == "get_brand_wcpay_request:ok") {	
		var orderid = '<?php echo $orderid; ?>';
		var money = '<?php echo $money; ?>';
		mui.ajax(edu_host + '/index.php/Mq/Order/pay/orderid/'+ orderid+'/money/'+money, {
			type: "post",
			success: function(data) {
				if (data == "yes") {
					mui.toast("支付成功");
					setTimeout(function() {
						document.location.href = "../../result.html?orderid="+orderid+"&money="+money;
					}, 1000);
				} else {
					mui.toast("支付成功,但状态失败");
					setTimeout(function() {
						document.location.href = "../../result.html";
					}, 1000);
				}
			}
		});
	} else {
		mui.toast('支付失败');
		setTimeout(function() {
			document.location.href = "../../order.html";
		}, 1000);
	}
}
);
}

function callpay() {
	if (typeof WeixinJSBridge == "undefined") {
		if (document.addEventListener) {
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		} else if (document.attachEvent) {
			document.attachEvent('WeixinJSBridgeReady', jsApiCall);
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	} else {
		jsApiCall();
	}
}
window.onload = function() {
		
		callpay();
}</script>
	</head>
</html>