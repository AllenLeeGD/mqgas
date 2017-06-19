<?php
$code = $_GET['code'];
//$loginType = $_GET['state'];
if ($code != "") {
	//通过code换取网页授权access_token
	$weixin = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxc49b96e815af547c&secret=4413e0fa4d155ebd0b7a724b52427634&code=" . $code . "&grant_type=authorization_code");
	//对JSON格式的字符串进行编码
	$jsondecode = json_decode($weixin);
	//转换成数组
	$array = get_object_vars($jsondecode);
	//输出openid
	$openid = $array['openid'];
	//输出access_token
	$access_token = $array['access_token'];
	//拉取用户信息
	$userinfo = json_decode(file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN"));
	$nickname = $userinfo->nickname;
//	$headicon = $userinfo->headimgurl;
//	if(empty($headicon)){
		$headicon= "../images/nohead.png";
//	}
	if(empty($nickname)){
		$nickname= "新用户";
	}
	header("Location: ../index.php/Member/Reg/login/openid/".base64_encode($openid)."/nickname/".base64_encode($nickname)."/headicon/".base64_encode($headicon));
//	echo $nickname;
	exit();
}
?>