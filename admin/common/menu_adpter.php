<?php
if ($_SESSION['admin_type'] == "admin") {
	include ("common/admin_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
} else if ($_SESSION['admin_type'] == "caiwu") {
	include ("common/caiwu_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
} else if ($_SESSION['admin_type'] == "biz") {
	include ("common/biz_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
} else if ($_SESSION['admin_type'] == "huawu") {
	include ("common/huawu_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
} else if ($_SESSION['admin_type'] == "piaofang") {
	include ("common/piaofang_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
} else if ($_SESSION['admin_type'] == "yingye") {
	include ("common/yingye_menu.html");
	//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
}
?>