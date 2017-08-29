<?php
//$param = "appid=wxc49b96e815af547c&mch_id=1350417201&nonce_str=".uniqid()."&product_id=123456&time_stamp=".time();
//$sign=strtoupper(md5($param."&key=4413e0fa4d155ebd0b7a724b52427634"));
//$result = "weixin://wxpay/bizpayurl?".$param."&sign=".$sign;
//echo $result;
//设置静态链接
include_once("WxPayPubHelper.php");
$nativeLink = new NativeLink_pub();

//$product_id = $_GET["orderid"]."_".$_GET["totalfee"];
$product_id = $_GET["orderid"];
//自定义商品id
$nativeLink -> setParameter("product_id", "123456");
////商品id
////获取链接
$product_url = $nativeLink -> getUrl();
////使用短链接转换接口
//$shortUrl = new ShortUrl_pub();
////设置必填参数
//$shortUrl -> setParameter("long_url", $product_url);
////URL链接
////获取短链接
//$codeUrl = $shortUrl -> getShortUrl();
////$this -> assign('product_url', $product_url);
////$this -> assign('codeUrl', $codeUrl);
////$this -> display();
echo $product_url;
?>