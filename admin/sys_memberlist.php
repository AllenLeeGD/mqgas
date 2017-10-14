<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>会员资料管理</title>
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
				if($_SESSION['admin_type']=="admin"){
					include("common/admin_menu.html");
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
							会员资料管理 <small>修改会员资料</small>
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
											<a id="addBtn" href="sys_memberadd.php?tag=memberadmin&item=1">
												新增客户信息
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
									<a href="sys_memberlist.php?tag=memberadmin=1"> 会员资料管理 </a>
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
												 管理列表
											</a>
										</li>
									</ul>
									<div class="table-container">
										<table class="table table-bordered table-hover table-custom" id="datatable_orders1">
											<thead>
												<tr role="row" class="heading">
													<th> 姓名 </th>
													<th style="text-align: center;"> 联系电话 </th>
													<th style="width:150px;text-align: center;"> 用户组 </th>
													<th style="width:150px;text-align: center;"> 业务员 </th>
													<th style="width:100px;text-align: center;"> 分类 </th>
													<th style="width:160px;text-align: center;"> 操作 </th>
												</tr>
												<tr role="row">
													<td>
														<input id="realname_search" type="text" class="form-control form-filter" name="realname_search" placeholder="请输入"> 
													</td>
													<td>
														<input id="mobile_search" type="text" class="form-control form-filter" name="mobile_search" placeholder="请输入"> 
													</td>
													<td>
														 
													</td>
													<td>
														<input id="yewuname_search" type="text" class="form-control form-filter" name="yewuname_search" placeholder="请输入"> 
													</td>
													<td>
														 <select class="form-control form-filter font-size-12" id="membertype_search" name="membertype_search">
																<option value="">全部</option>
																<option value="1">居民</option>
																<option value="2">小工商</option> 
																<option value="3">大工商</option>																
															</select>
													</td>
													<td>
														<div class="margin-bottom-5">
															<button id="btnSearch" class="btn btn-sm yellow filter-submit margin-bottom">
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
		<div id="view_data"></div>
		<div id="ajax-view" class="modal fade" tabindex="-1"></div>
		<div id="ajax-send" class="modal fade" tabindex="-1"></div>
			<div id="ajax-pddetail" class="modal fade" tabindex="-1"></div>
			<div class="modal fade" id="do_out" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">退户处理</h4>
						</div>
						<div class="modal-body">
							当前用户是否确定退户？
						</div>
						<form action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div id="realnamegroup" class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-3">退户原因 <span class="required"> </span> </label>
									<div class="col-md-5">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<textarea id="outreason" rows="6" style="width: 300px;" maxlength="120" class="form-control"></textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
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
		<script src="assets/scripts/admin/sys_memberlist.js"></script>
		<script>
			jQuery(document).ready(function() {
				$(".fancybox-button").live("click", function(event) {
					var href = $(this).attr('href');
					$.fancybox({
						'href': href
					});
					return false;
				});
				App.init();
			});
		</script>
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>