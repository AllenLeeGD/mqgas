<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>订单管理</title>
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
			if ($_SESSION['admin_type'] == "admin") {
				include ("common/admin_menu.html");
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
							<h3 class="page-title"> 订单管理 <small>配送、退款处理</small></h3>
							<ul class="page-breadcrumb breadcrumb">

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
									<a href="sys_orderlist.php?tag=productadmin&item=1">
										订单管理
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
										<li id="wait_tab" class="active">
											<a href="#tab_2" data-toggle="tab">
												待处理 <span id="waitnum" class="badge badge-danger"> </span>
											</a>
										</li>
										<li id="success_tab">
											<a href="#tab_6" data-toggle="tab">
												派送中
											</a>
										</li>
										<li id="complete_tab">
											<a href="#tab_7" data-toggle="tab">
												已完成
											</a>
										</li>
									</ul>

									<div class="portlet-body">
										<div class="table-container">

											<table class="table table-bordered" id="datatable_orders">
												<thead>
													<tr role="row" class="heading">
														<th style="width:15%"> 订单信息 </th>
														<th style="width:10%"> 单价 </th>
														<th style="width:7%"> 数量 </th>
														<th style="width:13%"> 购买人 </th>
														<th style="width:10%"> 状态 </th>
														<th style="width:10%"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															<input id="keyword_search" type="text" class="form-control form-filter" name="keyword_search" placeholder="请输入订单编号">
														</td>
														<td></td>
														<td></td>
														<td>
															<input id="buyername_search" type="text" class="form-control form-filter" name="buyername_search" placeholder="请输入购买人关键字">
														</td>
														<td>
															<select class="form-control form-filter font-size-12" id="status_search" name="status_search">
																<option value="">全部</option>
																<option value="0">现金待派送</option>
																<option value="7">微信待派送</option> 
																<option value="2">申请退款</option>
																<option value="4">完成退款</option>
																<option value="3">拒绝退款</option>
																<option value="5">已派送</option>
																<option value="p1">现金支付</option>
																<option value="p0">微信支付</option>
															</select>
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
			
			<div class="modal fade" id="do_refund" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">同意退款</h4>
						</div>
						<div class="modal-body">
							是否同意退款并且已经将金额返还给该客户？
						</div>
						<form action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div id="realnamegroup" class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-2">留言 <span class="required"> </span> </label>
									<div class="col-md-5">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<textarea id="refundMessage" rows="6" style="width: 300px;" maxlength="120" class="form-control"></textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn blue" onclick="doRefund()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="do_cancelrefund" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">拒绝退款</h4>
						</div>
						<div class="modal-body">
							是否拒绝退款？
						</div>
						<form action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div id="realnamegroup" class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-2">拒绝理由 <span class="required"> </span> </label>
									<div class="col-md-5">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<textarea id="refuseMessage" rows="6" style="width: 300px;" maxlength="120" class="form-control"></textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn red" onclick="doCancelRefund()">
							拒绝
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
			<script src="assets/scripts/admin/sys_orderlist.js"></script>
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