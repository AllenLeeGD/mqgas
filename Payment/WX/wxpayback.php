<?php
$code = $_GET['code'];
$state = $_GET['state'];
if ($code != "") {
	//通过code换取网页授权access_token
	$weixin = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3fc8241ec665d2e0&secret=0a370519f44de04199b9e70609d9e49f&code=" . $code . "&grant_type=authorization_code");
	//对JSON格式的字符串进行编码
	$jsondecode = json_decode($weixin);
	//转换成数组
	$array = get_object_vars($jsondecode);
	//输出openid
	$openid = $array['openid'];
	session_start();
	$params = explode("-",$state);
	$orderid = $params[0];
	$money = $params[1];
	$couponid = $params[2];
	$_SESSION["orderid"] = $orderid;
	$_SESSION["money"] = $money;
	$_SESSION["couponid"] = $couponid;
	$_SESSION["openid"] = $openid;
	header("Location: Pay.php");
//	echo $state;
}
?>