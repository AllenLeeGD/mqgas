<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->

	<head>
		<meta charset="utf-8" />
		<title>数据统计</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<?php include("common/agent_style.html") ?>
	</head>
	<!-- END HEAD -->
	<!-- BEGIN BODY -->

	<body class="page-header-fixed">
		<!-- BEGIN HEADER -->
		<div class="header navbar navbar-fixed-top">
			<?php include("common/admin_header.html") ?>
		</div>
		<!-- END HEADER -->
		<div class="clearfix"></div>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<?php
				if($_SESSION['admin_type']=="admin"){
					include("common/admin_menu.html");
				}else if($_SESSION['admin_type']=="gjadmin"){
					include("common/admingj_menu.html");
				}else if($_SESSION['admin_type']=="ptadmin"){
					include("common/adminpt_menu.html");
				}else if($_SESSION['admin_type']=="cwadmin"){
					include("common/admincw_menu.html");
				}
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
							<h3 class="page-title">
							数据统计 <small>有帮平台各类数据的统计信息</small>
							</h3>
							<ul class="page-breadcrumb breadcrumb">
								<li>
									<a href="admin_home.php?item=0"> 管理首页 </a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									平台管理
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_noticelist.php?tag=sysadmin&item=0"> 数据统计 </a>
								</li>
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
					<!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="fa fa-user"></i>
								</div>
								<div class="details">
									<div id="stu_count" class="number">

									</div>
									<div class="desc">
										学生
									</div>
								</div>
								<a class="more" href="student_reviewlist.php?tag=studentadmin&item=0"> 查看 <i class="m-icon-swapright m-icon-white"></i> </a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="fa fa-user-md"></i>
								</div>
								<div class="details">
									<div id="tutor_count" class="number">

									</div>
									<div class="desc">
										家教
									</div>
								</div>
								<a class="more" href="tutor_reviewlist.php?tag=tutoradmin&item=0"> 查看 <i class="m-icon-swapright m-icon-white"></i> </a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="fa fa-users"></i>
								</div>
								<div class="details">
									<div id="org_count" class="number">

									</div>
									<div class="desc">
										机构
									</div>
								</div>
								<a class="more" href="org_reviewlist.php?tag=orgadmin&item=0"> 查看 <i class="m-icon-swapright m-icon-white"></i> </a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="fa fa-sitemap"></i>
								</div>
								<div class="details">
									<div id="school_count" class="number">

									</div>
									<div class="desc">
										学校
									</div>
								</div>
								<a class="more" href="school_reviewlist.php?tag=schooladmin&item=0"> 查看 <i class="m-icon-swapright m-icon-white"></i> </a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-11 col-sm-11">
							<!-- BEGIN PORTLET-->
							<div style="margin-bottom: 10px;"><span class="help-block"> 在线用户查询范围 </span>
								<div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy" data-date="10/11/2012">
									<input class="form-control" readonly="readonly" type="text" name="from" id="user_start">
									<span class="input-group-addon"> 到 </span>
									<input class="form-control" readonly="readonly" type="text" name="to" id="user_end">
									<span class="input-group-btn">
										<button id="searchuser" class="btn green" type="button">
											<i class="fa fa-search"></i>
											查询
										</button>
									</span>
								</div>
							</div>
							
							<div class="portlet solid bordered light-grey">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-bar-chart-o"></i>在线用户数量
									</div>
									<!--
									<div class="tools">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn default btn-sm active">
												<input type="radio" name="options" class="toggle" id="option1">
												Users </label>
											<label class="btn default btn-sm">
												<input type="radio" name="options" class="toggle" id="option2">
												Feedbacks </label>
										</div>
									</div>
									-->
								</div>
								<div class="portlet-body">
									<!--
									<div id="site_statistics_loading">
										<img src="assets/img/loading.gif" alt="loading"/>
									</div>
									-->
									<div id="site_statistics_content">
										<div id="chart_income" class="chart"></div>
									</div>
								</div>
							</div>
							<!-- END PORTLET-->
						</div>
						<div class="col-md-11 col-sm-11">
							<!-- BEGIN PORTLET-->
							<div style="margin-bottom: 10px;"><span class="help-block"> 交易量查询范围 </span>
								<div class="input-group input-large date-picker input-daterange" data-date-format="yyyy/mm/dd" data-date="2012/10/11">
									<input class="form-control" readonly="readonly" type="text" name="from" id="order_start">
									<span class="input-group-addon"> 到 </span>
									<input class="form-control" readonly="readonly" type="text" name="to" id="order_end">
									<span class="input-group-btn">
											<button id="searchorder" class="btn green" type="button">
											<i class="fa fa-search"></i>
											查询
										</button>
									</span>
								</div>
							</div>
							<div class="portlet solid light-grey bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-bullhorn"></i>交易量
									</div>
									<!--
									<div class="tools">
										<div class="btn-group pull-right" data-toggle="buttons">
											<a href="" class="btn blue btn-sm active"> Users </a>
											<a href="" class="btn blue btn-sm"> Orders </a>
										</div>
									</div>
									-->
								</div>
								<div class="portlet-body">
									<!--
									<div id="site_activities_loading">
										<img src="assets/img/loading.gif" alt="loading"/>
									</div>
									-->
									<div id="site_activities_content">
										<div id="chart_comment" class="chart"></div>
									</div>
								</div>
							</div>
							<!-- END PORTLET-->
						</div>
					</div>
					<!-- END PAGE CONTENT-->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<?php include("common/admin_dialog.html") ?>
		<!-- END CONTAINER -->
		<!-- BEGIN FOOTER -->
		<?php include("common/footer.html") ?>
		<!-- END FOOTER -->
		<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="assets/plugins/respond.min.js"></script>
		<script src="assets/plugins/excanvas.min.js"></script>
		<![endif]-->
		<script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
		<?php include("common/script.html") ?>
		<script type="text/javascript" src="assets/plugins/data-tables/jquery.dataTables.js"></script>
		<script type="text/javascript" src="assets/plugins/data-tables/DT_bootstrap.js"></script>
		<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/plugins/flot/jquery.flot.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.pie.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.stack.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.crosshair.min.js"></script>
		<script src="assets/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
		<script src="assets/scripts/core/datatable.js"></script>
		<!--<script type="text/javascript" src="assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>-->
		<script src="assets/scripts/admin/sys_statistical.js"></script>
		<!-- END JAVASCRIPTS -->
		<script>
			jQuery(document).ready(function() {
				/*$(".fancybox-button").live("click", function(event) {
					var href = $(this).attr('href');
					$.fancybox({
						'href': href
					});
					return false;
				});*/
				App.init();
			});
		</script>
	</body>
	<!-- END BODY -->

</html>