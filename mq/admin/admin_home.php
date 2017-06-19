<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->

	<head>
		<meta charset="utf-8" />
		<title>管理员主页</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />

		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<?php include("common/agent_style.html")
		?>
	</head>
	<!-- END HEAD -->
	<!-- BEGIN BODY -->

	<body class="page-header-fixed">
		<!-- BEGIN HEADER -->
		<div class="header navbar navbar-fixed-top">
			<?php include("common/admin_header.html")
			?>
		</div>
		<!-- END HEADER -->
		<div class="clearfix"></div>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<?php
//			if ($_SESSION['admin_type'] == "admin") {
				include ("common/admin_menu.html");
				//echo "未登录 或 服务器配置错误，不支持SESSION".$_SESSION['admin_type'];
//			}
			?>
			<!-- BEGIN CONTENT -->
			<div class="page-content-wrapper">
				<div class="page-content">
					<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

					<!-- /.modal -->
					<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
					<!-- BEGIN STYLE CUSTOMIZER -->

					<!-- END STYLE CUSTOMIZER -->
					<!-- BEGIN PAGE HEADER-->
					<div class="row">
						<div class="col-md-12">
							<!-- BEGIN PAGE TITLE & BREADCRUMB-->
							<h3 class="page-title"> 管理首页 <small>&nbsp;</small></h3>
							<ul class="page-breadcrumb breadcrumb">
								<li>
									<i class="fa fa-home"></i>
									<a href="admin_home.html?item=0">
										管理首页
									</a>
								</li>
								<li>
									<a href=""></a>

								</li>
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
					<!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="fa fa-user"></i>
								</div>
								<div class="details">
									<div id="member_count" class="number">

									</div>
									<div class="desc">
										用户
									</div>
								</div>
								<a class="more" href="sys_memberlist.php?tag=memberadmin&item=1">
									查看 <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="fa fa-credit-card"></i>
								</div>
								<div class="details">
									<div id="order_count" class="number">

									</div>
									<div class="desc">
										订单
									</div>
								</div>
								<a class="more" href="sys_orderlist.php?tag=productadmin&item=1">
									查看 <i class="m-icon-swapright m-icon-white"></i>
								</a>
							</div>
						</div>
					</div>
					<!-- BEGIN DASHBOARD STATS -->

				</div>
				<!-- END CONTENT -->
			</div>
			<?php include("common/admin_dialog.html")
			?>
			<!-- END CONTAINER -->
			<!-- BEGIN FOOTER -->
			<?php include("common/footer.html")
			?>
			<!-- END FOOTER -->
			<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
			<!-- BEGIN CORE PLUGINS -->
			<!--[if lt IE 9]>
			<script src="assets/plugins/respond.min.js"></script>
			<script src="assets/plugins/excanvas.min.js"></script>
			<![endif]-->
			<script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
			<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
			<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

			<?php include("common/script.html")
			?>
			<script src="assets/plugins/flot/jquery.flot.min.js"></script>
			<script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
			<script src="assets/plugins/flot/jquery.flot.pie.min.js"></script>
			<script src="assets/plugins/flot/jquery.flot.stack.min.js"></script>
			<script src="assets/plugins/flot/jquery.flot.crosshair.min.js"></script>
			<script src="assets/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
			<script src="assets/scripts/admin/admin_home.js"></script>
			<!-- END PAGE LEVEL STYLES -->
			<script>jQuery(document).ready(function() {
	// initiate layout and plugins
	App.init();
});</script>
			<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->

</html>