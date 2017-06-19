<?php
ini_set('display_errors',1);            //错误信息  
ini_set('display_startup_errors',1);    //php启动错误信息  
error_reporting(-1);     
$orderid = $_GET['payorderid'];
$openid = $_GET['payopenid'];
$money = $_GET['paymoney'];
header("Location: Pay.php?orderid=".$orderid."&money=".$money."&openid=".$openid);
//print_r("orderid".$orderid);
//header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3fc8241ec665d2e0&redirect_uri=http%3A%2F%2Fapp.youbanghulian.com%2FPayment%2FWX%2FPay.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
//echo $money;
?>