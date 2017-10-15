<?php
//	$adminid = $_SESSION["adminid"];
	$name = $_SESSION["name"];
	$admintype = $_SESSION["admin_type"];	
	if ( empty($name) || empty($admintype)) {
		echo "<script>window.location.href='admin_login.html';</script>";
	}else{
		//判断权限
		$url = $_SERVER['PHP_SELF'];  
		$filename = end(explode('/',$url));
		$menulist = array();
		if($admintype!='admin' && $admintype!='guanli'){
			if($admintype=='huawu'){
				$menulist = array('admin_home.php','sys_orderlist.php');
				if(!in_array($filename,$menulist)){
					echo "<script>window.location.href='admin_login.html';</script>";
				}
			}else if($admintype=='biz'){
				$menulist = array('admin_home.php','sys_pricemain.php','cw_sk.php','sys_price.php','sys_price_add.php','sys_price_edit.php');
				if(!in_array($filename,$menulist)){
					echo "<script>window.location.href='admin_login.html';</script>";
				}
			}else if($admintype=='caiwu'){
				$menulist = array('admin_home.php','sys_sell_analyse.php','sys_sellmoney_analyse.php','sys_mdxs_analyse.php','cw_sk.php','cw_php.php','cw_mtsj.php','cw_sk_add.php','cw_sk_edit.php','cw_php_add.php','cw_php_edit.php','cw_mtsj_add.php','cw_mtsj_edit.php');
				if(!in_array($filename,$menulist)){
					echo "<script>window.location.href='admin_login.html';</script>";
				}
			}else if($admintype=='piaofang'){
				$menulist = array('admin_home.php','sys_orderlist_pf.php','sys_orderlist_hsp.php');
				if(!in_array($filename,$menulist)){
					echo "<script>window.location.href='admin_login.html';</script>";
				}
			}else if($admintype=='yingye'){
				$menulist = array('admin_home.php','sys_orderlist_md.php','sys_carsdaily.php','sys_songqidaily.php','sys_checkrecall.php','sys_bottle_main.php','sys_bottle_analyse.php','sys_sell_analyse.php','sys_sellmoney_analyse.php','sys_mdxs_analyse.php','sys_carsdaily_add.php','sys_carsdaily_edit.php','sys_songqidaily_edit.php','sys_songqidaily_add.php','sys_bottle.php','sys_bottle_add.php','sys_bottle_edit.php','sys_check.php','sys_check_add.php','sys_check_edit.php','sys_recall.php','sys_recall_add.php','sys_recall_edit.php');
				if(!in_array($filename,$menulist)){
					echo "<script>window.location.href='admin_login.html';</script>";
				}
			}
		}
	}
?>
<!-- BEGIN TOP NAVIGATION BAR -->
<div class="header-inner">
	<!-- BEGIN LOGO -->
	<!--<img src="assets/img/logo_in.png" alt="logo" style="width:226px;" />-->
	<!-- END LOGO -->
	<!-- BEGIN RESPONSIVE MENU TOGGLER -->
	<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="assets/img/menu-toggler.png" alt="" /> </a>
	<!-- END RESPONSIVE MENU TOGGLER -->
	<!-- BEGIN TOP NAVIGATION MENU -->
	<ul class="nav navbar-nav pull-right">
		<!-- BEGIN USER LOGIN DROPDOWN -->
		<li class="dropdown user">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <img alt="" src="assets/img/usr_small_agent.jpg" style="width:29px;" /> <span id="provider_username" class="username"> </span> <i class="fa fa-angle-down"></i> </a>
			<ul class="dropdown-menu">
				<!--
				<li>
				<a href="extra_profile.html">
				<i class="fa fa-user"></i> 帐户信息
				</a>
				</li>

				<li>
				<a href="inbox.html">
				<i class="fa fa-envelope"></i> My Inbox
				<span class="badge badge-danger">
				3
				</span>
				</a>
				</li>
				<li>
				<a href="#">
				<i class="fa fa-tasks"></i> My Tasks
				<span class="badge badge-success">
				7
				</span>
				</a>
				</li>
				<li class="divider">
				</li>-->
				<li>
					<a href="#change_pwd" data-toggle="modal"> <i class="fa fa-key"></i> 修改密码 </a>
				</li>
				<li>
					<a href="javascript:;" id="trigger_fullscreen"> <i class="fa fa-arrows"></i> 全屏 </a>
				</li>
				<!--<li>
				<a href="extra_lock.html">
				<i class="fa fa-lock"></i> Lock Screen
				</a>
				</li>-->
				<li>
					<a href="admin_login.html"> <i class="fa fa-key"></i> 安全退出 </a>
				</li>
			</ul>
		</li>
		<!-- END USER LOGIN DROPDOWN -->
	</ul>
	<!-- END TOP NAVIGATION MENU -->

</div>
<!-- END TOP NAVIGATION BAR -->
<script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>