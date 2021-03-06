<?php

//getsession
function getsessionkeyval($key) {
	return session($key);
}
function getStatus($status){
	if($status==0){
		return "待送货";
	}else if($status==1){
		return "待送货";
	}else if($status==2){
		return "申请退款";
	}else if($status==3){
		return "拒绝退款";
	}else if($status==4){
		return "完成退款";
	}else if($status==5){
		return "已派送";
	}else if($status==6){
		return "微信待付款";
	}else if($status==7){
		return "微信已付款，待送货";
	}else if($status==8){
		return "已完成";
	}		
}
function strCut($str,$length)//$str为要进行截取的字符串，$length为截取长度（汉字算一个字，字母算半个字）
{
	$str = trim($str);
	$string = "";
	if(strlen($str) > $length)
	{
		for($i = 0 ; $i<$length ; $i++)
		{
			if(ord($str) > 127)
			{
				$string .= $str[$i] . $str[$i+1] . $str[$i+2];
				$i = $i + 2;
			}
			else
			{
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
	$check = getFromSession($sessionid. ".userid");
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

function strencode($string,$encryptkey) {   
    $string = base64_encode ( $string );   
    $key = md5 ($encryptkey);   
    $len = strlen ( $key );  
    $code = '';   
    for($i = 0; $i < strlen ( $string ); $i ++) {       
                $k = $i % $len;       
                $code .= $string [$i] ^ $key [$k];   
    }   
    return base64_encode ( $code );   
}  

/**
 * 根据传入的区ID，省份、城市、区的字符串
 */
function getCityInfo($district) {
	$query = new \Think\Model();
	$condition_sql = "select a.cityname as p,b.cityname as c,c.cityname as d from basecityinfo as a left join basecityinfo as b on a.pcityid = b.cityid
 left join basecityinfo as c on b.pcityid = c.cityid where a.cityid='$district'";
	$result = $query -> query($condition_sql);
	if (!empty($result) && !empty($result[0])) {
		echo $result[0]['d'] . " " . $result[0]['c'] . ' ' . $result[0]['p'];
	} else {
		echo "";
	}
}
?>
