<?php

//getsession
function getsessionkeyval($key) {
	return session($key);
}
function emptyZero($input){
	if($input == "0"){
		return "";
	}else{
		return $input;
	}
}

function computeWeight($qx,$yx,$zf,$jf,$two,$five){
	return (floatval($qx)+floatval($yx))*50/1000+(floatval($zf)+floatval($jf))*14.5/1000+floatval($two)*2/1000+floatval($five)*5/1000;
}

function changetype($changetype) {
	if($changetype==1){
		return "出-瓶换瓶";
	}else if($changetype==2){
		return "出-门店退瓶到气库";
	}else if($changetype==3){
		return "出-借瓶阀";
	}else if($changetype==4){
		return "入-气库发瓶到门店";
	}else if($changetype==5){
		return "入-收押金";
	}else if($changetype==6){
		return "入-客户退瓶";
	}else if($changetype==7){
		return "入-瓶换瓶";
	}else if($changetype==8){
		return "出-退押金";
	}else if($changetype==0){
		return "其它";
	}
}

/**
 * 获得钢瓶编码.
 */
function gpcode($pname,$gpname,$jname,$rname,$fname) {
	if ($fname=="高压阀") {
		return "17";
	} else if ($fname=="中压阀") {
		return "18";
	} else if ($fname=="低压阀") {
		return "19";
	} else if ($fname=="低压阀") {
		return "19";
	} else if ($pname=="15KG" && $jname=="直阀") {
		return "1";
	} else if ($pname=="15KG" && $jname=="角阀" && $gpname=="专用瓶") {
		return "2";
	} else if ($pname=="15KG" && $jname=="角阀" && $gpname=="翻新瓶") {
		return "3";
	} else if ($pname=="2KG" && $gpname=="专用瓶") {
		return "4";
	} else if ($pname=="2KG" && $gpname=="翻新瓶") {
		return "5";
	} else if ($pname=="5KG" && $gpname=="专用瓶") {
		return "6";
	} else if ($pname=="5KG" && $gpname=="翻新瓶") {
		return "7";
	} else if ($pname=="50KG" && $rname=="液相" && $gpname=="专用瓶") {
		return "8";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="专用瓶") {
		return "9";
	} else if ($pname=="50KG" && $rname=="无臭" && $gpname=="专用瓶") {
		return "10";
	} else if ($pname=="50KG" && $rname=="液相" && $gpname=="翻新瓶") {
		return "11";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="翻新瓶") {
		return "12";
	} else if ($pname=="15KG" && $gpname=="旧杂瓶") {
		return "13";
	} else if ($pname=="5KG" && $gpname=="旧杂瓶") {
		return "14";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="旧杂瓶") {
		return "15";
	}else if ($pname=="50KG" && $rname=="液相" && $gpname=="旧杂瓶") {
		return "16";
	}
}

/**
 * 获得钢瓶类型名称.
 */
function gpcodename($pname,$gpname,$jname,$rname,$fname) {
	if ($fname=="高压阀") {
		return "高压阀";
	} else if ($fname=="中压阀") {
		return "中压阀";
	} else if ($fname=="低压阀") {
		return "低压阀";
	} else if ($fname=="低压阀") {
		return "低压阀";
	} else if ($pname=="15KG" && $jname=="直阀") {
		return "15KG直阀";
	} else if ($pname=="15KG" && $jname=="角阀" && $gpname=="专用瓶") {
		return "15KG角阀专用瓶";
	} else if ($pname=="15KG" && $jname=="角阀" && $gpname=="翻新瓶") {
		return "15KG角阀翻新瓶";
	} else if ($pname=="2KG" && $gpname=="专用瓶") {
		return "2KG专用瓶";
	} else if ($pname=="2KG" && $gpname=="翻新瓶") {
		return "2KG翻新瓶";
	} else if ($pname=="5KG" && $gpname=="专用瓶") {
		return "5KG专用瓶";
	} else if ($pname=="5KG" && $gpname=="翻新瓶") {
		return "5KG翻新瓶";
	} else if ($pname=="50KG" && $rname=="液相" && $gpname=="专用瓶") {
		return "50KG专用瓶液相";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="专用瓶") {
		return "50KG专用瓶气相";
	} else if ($pname=="50KG" && $rname=="无臭" && $gpname=="专用瓶") {
		return "50KG专用瓶无臭";
	} else if ($pname=="50KG" && $rname=="液相" && $gpname=="翻新瓶") {
		return "50KG翻新瓶液相";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="翻新瓶") {
		return "50KG翻新瓶气相";
	} else if ($pname=="15KG" && $gpname=="旧杂瓶") {
		return "15KG旧杂瓶";
	} else if ($pname=="5KG" && $gpname=="旧杂瓶") {
		return "5KG旧杂瓶";
	} else if ($pname=="50KG" && $rname=="气相" && $gpname=="旧杂瓶") {
		return "50KG旧杂瓶气相";
	}else if ($pname=="50KG" && $rname=="液相" && $gpname=="旧杂瓶") {
		return "50KG旧杂瓶液相";
	}else if(!empty($pname)){
		return $pname.$gpname;
	}else if(!empty($fname)){
		return $fname;
	}
}
/**
 * 获得类型.
 */
function memberType($status) {
	if ($status == 0) {
		return "居民用户";
	} else if ($status == 1) {
		return "小工商";
	} else if ($status == 2) {
		return "大工商";
	}
}

/**
 * 获得类型.
 */
function getTypeStr($type) {
	if ($type == 1) {
		return "退户瓶";
	} else if ($type == 2) {
		return "还瓶";
	} else if ($type == 3) {
		return "回收杂瓶";
	} else if ($type == 4) {
		return "回流瓶";
	} else if ($type == 5) {
		return "入重瓶";
	} else if ($type == 6) {
		return "借出瓶";
	} else if ($type == 7) {
		return "押金瓶";
	} else if ($type == 8) {
		return "回收杂瓶";
	} else if ($type == 9) {
		return "回流瓶";
	} else if ($type == 10) {
		return "售重瓶";
	} else if ($type == 0) {
		return "其它";
	}
}

function getStatus($status) {
	if ($status == 0) {
		return "待送货";
	} else if ($status == 1) {
		return "待送货";
	} else if ($status == 2) {
		return "申请退款";
	} else if ($status == 3) {
		return "拒绝退款";
	} else if ($status == 4) {
		return "完成退款";
	} else if ($status == 5) {
		return "已派送";
	} else if ($status == 6) {
		return "微信待付款";
	} else if ($status == 7) {
		return "微信已付款，待送货";
	} else if ($status == 8) {
		return "已完成";
	} else if ($status == -1) {
		return "已取消";
	}
}

function getNewStatus($status,$jmstatus,$dgsstatus,$hspstatus) {
	if ($status == 0) {
		return "待送货";
	} else if ($status == 1) {
		return "待送货";
	} else if ($status == 2) {
		return "申请退款";
	} else if ($status == 3) {
		return "拒绝退款";
	} else if ($status == 4) {
		return "完成退款";
	} else if ($status == 5) {
		return "已派送";
	} else if ($status == 6) {
		return "微信待付款";
	} else if ($status == 7) {
		return "微信已付款，待送货";
	} else if ($status == 8) {
		return "已完成";
	} else if ($status == -1) {
		return "已取消";
	} else if($status == -7){//居民
		if ($jmstatus == -1) {
			return "已取消";
		} else if ($jmstatus == 0) {
			return "暂存";
		} else if ($jmstatus == 1) {
			return "已下单";
		} else if ($jmstatus == 2) {
			return "已分派片区";
		} else if ($jmstatus == 3) {
			return "已分配配送";
		} else if ($jmstatus == 4) {
			return "门店已收款";
		} else if ($jmstatus == 5) {
			return "门店已存款";
		} else if ($jmstatus == 6) {
			return "财务已核款";
		} else if ($jmstatus == 7) {
			return "月结客户已完成";
		} else if ($jmstatus == 8) {
			return "已送达客户";
		}
	} else if($status == -8){
		if ($hspstatus == -1) {
			return "已取消";
		} else if ($hspstatus == 0) {
			return "已下单";
		} else if ($hspstatus == 1) {
			return "已预派车辆";
		} else if ($hspstatus == 2) {
			return "已入库";
		} else if ($hspstatus == 3) {
			return "已核价";
		}
	} else if($status == -9){
		if ($dgsstatus == -1) {
			return "已取消";
		} else if ($dgsstatus == 0) {
			return "已下单";
		} else if ($dgsstatus == 1) {
			return "已预派车辆";
		} else if ($dgsstatus == 2) {
			return "已出库";
		} else if ($dgsstatus == 3) {
			return "已入库";
		} else if ($dgsstatus == 4) {
			return "已核价";
		}
	}
}

function strCut($str, $length)//$str为要进行截取的字符串，$length为截取长度（汉字算一个字，字母算半个字）
{
	$str = trim($str);
	$string = "";
	if (strlen($str) > $length) {
		for ($i = 0; $i < $length; $i++) {
			if (ord($str) > 127) {
				$string .= $str[$i] . $str[$i + 1] . $str[$i + 2];
				$i = $i + 2;
			} else {
				$string .= $str[$i];
			}
		}
		$string .= "...";
		return $string;
	}
	return $str;
}

//检查字符串是否为空
function checkstrrequire($str) {
	if (empty($str) || $str == "" || strlen($str) == 0) {
		return false;
	} else {
		return true;
	}
}

//字符串必须在某个长度范围之内
function checkstrlenrange($str, $begin, $end) {
	if (strlen($str) >= $begin && strlen($str) <= $end) {
		return true;
	} else {
		return false;
	}
}

//字符串不能小于某个长度
function checkstrlenmin($str, $len) {
	if (strlen($str) < $len) {
		return false;
	} else {
		return true;
	}
}

//字符串不能大于某个长度
function checkstrlenmax($str, $len) {
	if (strlen($str) > $len) {
		return false;
	} else {
		return true;
	}
}

/**
 * 生成缩略图
 * @author yangzhiguo0903@163.com
 * @param string     源图绝对完整地址{带文件名及后缀名}
 * @param string     目标图绝对完整地址{带文件名及后缀名}
 * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
 * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
 * @param int        是否裁切{宽,高必须非0}
 * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
 * @return boolean
 */
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0) {
	if (!is_file($src_img)) {
		return false;
	}
	$ot = fileext($dst_img);
	$otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
	$srcinfo = getimagesize($src_img);
	$src_w = $srcinfo[0];
	$src_h = $srcinfo[1];
	$type = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
	$createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

	$dst_h = $height;
	$dst_w = $width;
	$x = $y = 0;

	/**
	 * 缩略图不超过源图尺寸（前提是宽或高只有一个）
	 */
	if (($width > $src_w && $height > $src_h) || ($height > $src_h && $width == 0) || ($width > $src_w && $height == 0)) {
		$proportion = 1;
	}
	if ($width > $src_w) {
		$dst_w = $width = $src_w;
	}
	if ($height > $src_h) {
		$dst_h = $height = $src_h;
	}

	if (!$width && !$height && !$proportion) {
		return false;
	}
	if (!$proportion) {
		if ($cut == 0) {
			if ($dst_w && $dst_h) {
				if ($dst_w / $src_w > $dst_h / $src_h) {
					$dst_w = $src_w * ($dst_h / $src_h);
					$x = 0 - ($dst_w - $width) / 2;
				} else {
					$dst_h = $src_h * ($dst_w / $src_w);
					$y = 0 - ($dst_h - $height) / 2;
				}
			} else if ($dst_w xor $dst_h) {
				if ($dst_w && !$dst_h)//有宽无高
				{
					$propor = $dst_w / $src_w;
					$height = $dst_h = $src_h * $propor;
				} else if (!$dst_w && $dst_h)//有高无宽
				{
					$propor = $dst_h / $src_h;
					$width = $dst_w = $src_w * $propor;
				}
			}
		} else {
			if (!$dst_h)//裁剪时无高
			{
				$height = $dst_h = $dst_w;
			}
			if (!$dst_w)//裁剪时无宽
			{
				$width = $dst_w = $dst_h;
			}
			$propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
			$dst_w = (int)round($src_w * $propor);
			$dst_h = (int)round($src_h * $propor);
			$x = ($width - $dst_w) / 2;
			$y = ($height - $dst_h) / 2;
		}
	} else {
		$proportion = min($proportion, 1);
		$height = $dst_h = $src_h * $proportion;
		$width = $dst_w = $src_w * $proportion;
	}

	$src = $createfun($src_img);
	$dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
	$white = imagecolorallocate($dst, 255, 255, 255);
	imagefill($dst, 0, 0, $white);

	if (function_exists('imagecopyresampled')) {
		imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	} else {
		imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	}
	$otfunc($dst, $dst_img);
	imagedestroy($dst);
	imagedestroy($src);
	return true;
}

function fileext($file) {
	return pathinfo($file, PATHINFO_EXTENSION);
}

function getCommentType($commenttype) {
	if ($commenttype == 0) {
		return "<div style=\"color:#ff0000;text-align:center;\" title=\"好评\"><i class=\"fa fa-thumbs-o-up\"></i></div>";
	} else if ($commenttype == 1) {
		return "<div style=\"color:#ffcc00;text-align:center;\" title=\"中评\"><i class=\"fa fa-hand-o-right\"></i></div>";
	} else if ($commenttype == 2) {
		return "<div style=\"text-align:center;\" title=\"差评\"><i class=\"fa fa-thumbs-o-down\"></i></div>";
	}
}

//将某个值放入伪SESSION
function putInSession($name, $value) {
	S($name, $value, SESSION_TIME);
}

//从伪SESSION取值
function getFromSession($name) {
	return S($name);
}

//放入跨域安全头
function putHeader() {
	header('Access-Control-Allow-Origin:' . SITE_URL);
	header('Access-Control-Allow-Headers:X-Requested-With');
	$method = $_SERVER['REQUEST_METHOD'];
	if (strcmp($method, "OPTIONS") == 0) {
		exit ;
	}
}

function checkSession() {
	$sessionid = I("post.sessionid");
	$check = getFromSession($sessionid . ".userid");
	if (empty($check)) {
		echo "notlogin";
	}
}

function getObjFromPost($Array) {
	if ($Array && !empty($Array)) {
		$data;
		for ($i = 0; $i < sizeof($Array); $i++) {
			$item = $Array[$i];
			$data[$item] = I('post.' . $item);
		}
		return $data;
	} else {
		return null;
	}
}

function strencode($string, $encryptkey) {
	$string = base64_encode($string);
	$key = md5($encryptkey);
	$len = strlen($key);
	$code = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$k = $i % $len;
		$code .= $string[$i] ^ $key[$k];
	}
	return base64_encode($code);
}

/**
 * 写入操作日志
 */
function addLog($typeval, $userid, $remark) {
	$dao = M("Optlog");
	$userdao = M("Userinfo");
	$user_data = $userdao -> where("pid='$userid'") -> find();
	$data['pkid'] = uniqid();
	$data['opttime'] = time();
	$data['typeval'] = $typeval;
	$data['remark'] = $remark;
	$data['userid'] = $userid;
	$data['username'] = $user_data['realname'] . "(" . $user_data['name'] . ")";
	$dao -> add($data);
}
?>
