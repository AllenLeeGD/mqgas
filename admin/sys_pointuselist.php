<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>积分流水</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
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
							积分流水 <small>查询积分流水</small>
							</h3>
							<ul class="page-breadcrumb breadcrumb">
								<li class="btn-group">
									<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
									<span>
										功能菜单
									</span>
									<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="sys_pointpdadd.php?tag=sysadmin&item=4">
												新增积分商品
											</a>
										</li>
										<li>
											<a href="sys_pointpdlist.php?tag=sysadmin&item=4">
												积分商品
											</a>
										</li>
										<li>
											<a href="sys_pointuselist.php?tag=sysadmin&item=4">
												积分流水查询
											</a>
										</li>
										<li>
											<a href="sys_pointuse.php?tag=sysadmin&item=4">
												积分兑换订单管理
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="admin_home.php?item=0"> 管理首页 </a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									平台管理
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_pointuselist.php?tag=sysadmin&item=4"> 积分流水 </a>
								</li>
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
					<!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-md-12">
							<!-- Begin: life time stats -->
							<div class="portlet">

								<div class="portlet-body">
									<ul class="nav nav-tabs">
										<li id="li_general" class="active">
											<a href="#tab_general" data-toggle="tab">
												 流水列表
											</a>
										</li>
									</ul>
									<div class="table-container">
										<table class="table table-bordered table-hover table-custom" id="datatable_orders1">
											<thead>
												<tr role="row" class="heading">
													<th> 积分内容 </th>
													<th style="width:150px;text-align: center;"> 用户 </th>
													<th style="width:60px;text-align: center;"> 发生前 </th>
													<th style="width:60px;text-align: center;"> 积分 </th>
													<th style="width:60px;text-align: center;"> 发生后 </th>
													<th style="width:150px;text-align: center;"> 发生时间 </th>
													<th style="width:90px;text-align: center;"> 操作 </th>
												</tr>
												<tr role="row">
													<td></td>
													<td>
														<input type="text" class="form-control form-filter" style="font-size:12px;" name="userid_search" id="userid_search" placeholder="用户ID">
													</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td>
														<div class="margin-bottom-5">
															<button class="btn btn-sm yellow filter-submit margin-bottom">
																<i class="fa fa-search"></i> 搜索
															</button>
														</div>
													</td>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- End: life time stats -->
						</div>
					</div>
					<!-- END PAGE CONTENT-->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<div id="review_data"></div>
		<div id="ajax-productreview" class="modal fade" tabindex="-1"></div>
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
		<script src="assets/scripts/core/datatable.js"></script>
		<script type="text/javascript" src="assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
		<script src="assets/scripts/admin/sys_pointuselist.js"></script>
		
	</body>
	<!-- END BODY -->
</html>