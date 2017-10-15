<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>员工管理</title>
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
			<?php include("common/admin_header.php") ?>
		</div>
		<!-- END HEADER -->
		<div class="clearfix"></div>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<?php
//			if ($_SESSION['admin_type'] == "admin") {
				include ("common/menu_adpter.php");
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
							<h3 class="page-title"> 员工管理 <small>增加、编辑、删除员工信息</small></h3>
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
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=guanli">
												新增管理层
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=huawu">
												新增话务
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=biz">
												新增业务员
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=yingye">
												新增营业员
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=caiwu">
												新增财务
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=piaofang">
												新增港口票房
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=siji">
												新增司机
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=songqi">
												新增送气工
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=yayun">
												新增押运
											</a>
										</li>
										<li>
											<a href="sys_roleset_add.php?tag=sysadmin&item=4&roletype=chedui">
												新增车队负责人
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
									<a href="sys_roleset.php?tag=sysadmin&item=4">
										员工管理
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
										<li id="huawu_tab" class="active">
											<a href="#tab_2" data-toggle="tab">
												话务
											</a>
										</li>
										<li id="guanli_tab" >
											<a href="#tab_2" data-toggle="tab">
												管理层
											</a>
										</li>
										<li id="biz_tab">
											<a href="#tab_6" data-toggle="tab">
												业务员
											</a>
										</li>
										<li id="yingye_tab">
											<a href="#tab_6" data-toggle="tab">
												营业员
											</a>
										</li>
										<li id="caiwu_tab">
											<a href="#tab_1" data-toggle="tab">
												财务
											</a>
										</li>
										<li id="piaofang_tab">
											<a href="#tab_3" data-toggle="tab">
												港口票房
											</a>
										</li>
										<li id="siji_tab">
											<a href="#tab_4" data-toggle="tab">
												司机
											</a>
										</li>
										<li id="songqi_tab">
											<a href="#tab_5" data-toggle="tab">
												送气工
											</a>
										</li>
										<li id="yayun_tab">
											<a href="#tab_6" data-toggle="tab">
												押运
											</a>
										</li>
										<li id="chedui_tab">
											<a href="#tab_6" data-toggle="tab">
												车队负责人
											</a>
										</li>
									</ul>

									<div class="portlet-body">
										<div class="table-container">

											<table class="table table-bordered" id="datatable_orders">
												<thead>
													<tr role="row" class="heading">
														<th style="width:15%"> 姓名 </th>
														<th style="width:10%"> 账户名称 </th>
														<th style="width:7%"> 手机号码 </th>
														<th style="width:10%"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															<input id="realname_search" type="text" class="form-control form-filter" name="realname_search" placeholder="请输入员工姓名">
														</td>
														<td><input id="name_search" type="text" class="form-control form-filter" name="name_search" placeholder="请输入账户"></td>
														<td>
															<input id="mobile_search" type="text" class="form-control form-filter" name="mobile_search" placeholder="请输入手机号码">
														</td>

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
			<div class="modal fade" id="do_confirm" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">派送液化气</h4>
						</div>
						<div class="modal-body">
							是否派送此订单的液化气？
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn green" onclick="doConfirm()">
							确认
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>

			<div class="modal fade" id="do_complete" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">完成派送</h4>
						</div>
						<div class="modal-body">
							此订单是否已经配送成功？<span id="complete_order_id"></span>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="complete_send_btn" type="button" class="btn green" onclick="doComplete()">
							确认
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>

			<div class="modal fade" id="do_reset" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">重置密码</h4>
						</div>
						<div class="modal-body">
							是否将此员工密码重置为123456？
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn blue" onclick="doReset()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="do_delWorker" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">删除</h4>
						</div>
						<div class="modal-body">
							是否删除此员工？注意：此操作不可恢复!
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
			<script src="assets/scripts/admin/sys_roleset.js"></script>
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