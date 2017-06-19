<?php
	$adminid = $_SESSION["adminid"];
	$name = $_SESSION["name"];
	$admintype = $_SESSION["admin_type"];
	if (empty($adminid) || empty($name) || empty($admintype)) {
		echo "<script>window.location.href='admin_login.html';</script>";
	}else{
		//判断权限
		$url = $_SERVER['PHP_SELF'];  
		$filename = end(explode('/',$url));
		$menulist = array();
		if($admintype!='admin'){
			if($admintype=='huawu'){
				$menulist = array('admin_home.php','public_main.php','nonepublic_main.php','public_add.php','information_list.php','informationreply_list.php','information_add.php','information_edit.php','vote_main.php','votereport_main.php','vote_add.php','voteitem_add.php','voteitem_main.php','voteitemreport_main.php','qa_main.php','qa_mainresult.php','qa_mainlucky.php','qa_aadd.php','qa_add.php','qa_aedit.php','qa_aitem.php','qa_edit.php','qa_ladd.php','qa_ledit.php','qa_litem.php','qa_mainluckydetail.php','qa_qadd.php','qa_qedit.php','qa_qitem.php');
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