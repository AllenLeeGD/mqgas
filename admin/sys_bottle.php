<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>客户钢瓶管理</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
		<?php include("common/agent_style.html")
		?>
	</head>
	<!-- END HEAD -->
	<!-- BEGIN BODY -->
	<body class="page-header-fixed">
		<!-- BEGIN HEADER -->
		<div class="header navbar navbar-fixed-top">
			<?php include("common/admin_header.php")
			?>
		</div>
		<!-- END HEADER -->
		<div class="clearfix"></div>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<?php
			if ($_SESSION['admin_type'] == "admin") {
				include ("common/menu_adpter.php");
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
							<h3 class="page-title"> 客户钢瓶管理 <small>增加、编辑、删除客户钢瓶信息</small></h3>
							<ul class="page-breadcrumb breadcrumb">
								<li class="btn-group">
									<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
									<span> 功能菜单 </span>
									<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a id="addBtn" href="sys_check_add.php?tag=sysadmin&item=13">
												新增钢瓶信息
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="admin_home.php?item=0">
										管理首页
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									平台管理
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_bottle_main.php?tag=sysadmin&item=16">
										客户钢瓶管理
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="#">
										客户钢瓶明细
									</a>
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
									<ul class="nav nav-tabs nav-tabs-lg">
										<li id="mendian_tab" class="active">
											<a href="#tab_2" data-toggle="tab">
												<span id="membername"></span> 的钢瓶明细
											</a>
										</li>
									</ul>

									<div class="portlet-body">
										<div class="table-container">

											<table class="table table-bordered" id="datatable_orders">
												<thead>
													<tr role="row" class="heading">
														<th style="width:150px"> 日期 </th>
														<th style="width:150px"> 规格 </th>
														<th style="width:150px"> 配件 </th>
														<th style="width:150px"> 类型 </th>
														<th style="width:150px"> 数量 </th>
														<th style="width:200px"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															<input id="optdate_search" type="text" class="form-control form-filter" name="optdate_search" placeholder="请输入">
														</td>
														<td>
															<input id="pname_search" type="text" class="form-control form-filter" name="pname_search" placeholder="请输入">
														</td>
														<td>
															<input id="fname_search" type="text" class="form-control form-filter" name="fname_search" placeholder="请输入">
														</td>
														<td>
															<select class="form-control form-filter font-size-12" id="type_search" name="type_search">
																<option value="">全部</option>
																<option value="1">退户瓶</option>
																<option value="2">还瓶</option>
																<option value="3">回收杂瓶</option>
																<option value="4">回流瓶</option>
																<option value="5">入重瓶</option>
																<option value="6">借出瓶</option>
																<option value="7">押金瓶</option>
																<option value="8">回收杂瓶</option>
																<option value="9">回流瓶</option>
																<option value="10">售重瓶</option>																
															</select></td>
														<td></td>
														<td>
															<div class="margin-bottom-5">
																<button id="btnSearch" class="btn btn-sm yellow filter-submit margin-bottom">
																<i class="fa fa-search"></i> 搜索
																</button>
															</div></td>
													</tr>
												</thead>
												<tbody></tbody>
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
			<div id="agent_order_data"></div>
			<div id="view_data"></div>
			<div id="ajax-modal" class="modal fade" tabindex="-1"></div>
			<div id="ajax-send" class="modal fade" tabindex="-1"></div>
			<div id="ajax-pddetail" class="modal fade" tabindex="-1"></div>
			<div class="modal fade" id="do_delWorker" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">删除</h4>
						</div>
						<div class="modal-body">
							是否删除此记录？注意：此操作不可恢复!
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn red" onclick="doDelWorker()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
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
			<?php include("common/script.html")
			?>
			<script type="text/javascript" src="assets/plugins/data-tables/jquery.dataTables.js"></script>
			<script type="text/javascript" src="assets/plugins/data-tables/DT_bootstrap.js"></script>
			<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
			<script src="assets/scripts/core/datatable.js"></script>
			<script type="text/javascript" src="assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
			<script src="assets/scripts/admin/sys_bottle.js"></script>
			<script>jQuery(document).ready(function() {
	$(".fancybox-button").live("click", function(event) {
		var href = $(this).attr('href');
		$.fancybox({
			'href': href
		});
		return false;
	});
	App.init();
});</script>
			<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>