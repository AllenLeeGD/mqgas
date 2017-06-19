<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>积分兑换订单</title>
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
							积分兑换订单 <small>发货、撤销、查询订单</small>
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
									<a href="admin_home.php?php=0"> 管理首页 </a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									平台管理
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_pointuse.php?tag=sysadmin&item=4"> 积分兑换订单 </a>
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
										<li id="li_send" class="active">
											<a href="#tab_send" data-toggle="tab">
												 待处理
											</a>
										</li>
										<li id="li_finish">
											<a href="#tab_finish" data-toggle="tab">
												 已处理
											</a>
										</li>
									</ul>
									<div class="table-container">
										<table class="table table-bordered table-hover table-custom" id="datatable_orders1">
											<thead>
												<tr role="row" class="heading">
													<th style="width:250px;text-align: left;"> 订单信息 </th>
													<th> 商品 </th>
													<th style="width:150px;text-align: center;"> 操作 </th>
												</tr>
												<tr role="row">
													<td><input id="keyword_search" type="text" class="form-control form-filter" name="keyword_search" placeholder="请输入单号或用户名称关键字"></td>
													<td></td>
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
		<div id="ajax-vieworder" class="modal fade" tabindex="-1"></div>
		<div class="modal fade" id="del_confirm" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" style="padding:10px 20px 10px 20px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title" style="color:#cc0000;">撤销</h4>
					</div>
					<div class="modal-body">
						撤销后无法恢复，是否确认撤销该订单？
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">
							取消
						</button>
						<button id="confirm_send_btn" type="button" class="btn red" onclick="doDel()">
							确认
						</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<div class="modal fade" id="send_confirm" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" style="padding:10px 20px 10px 20px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">发货确认</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<form class="form-horizontal" role="form">
								<div id="expressnogroup" class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-2">快递单号
										<span class="required">
										 	
										</span>
									</label>
									<div class="col-md-4">
										<div class="input-icon right">
											<input id="expressno" type="text" class="form-control input-medium"/>
										</div>
									</div>
								</div>
								<div id="expressnamegroup" class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-2">快递公司
										<span class="required">
										 	
										</span>
									</label>
									<div class="col-md-3">
										<select id="expressname" name="expressname" class="form-control">
											<option value="">请选择...</option>
											<option value="顺风快递">顺风快递</option>
											<option value="韵达快递">韵达快递</option>
											<option value="申通快递">申通快递</option>
											<option value="中通快递">中通快递</option>
											<option value="汇通快递">汇通快递</option>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">
							取消
						</button>
						<button id="btnSend"  data-loading-text="确认中..." onclick="doSend()" type="button" class="btn blue">
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
		<script src="assets/scripts/admin/sys_pointuse.js"></script>
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