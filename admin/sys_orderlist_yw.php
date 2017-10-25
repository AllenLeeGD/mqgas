<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>业务员订单管理</title>
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
				include ("common/menu_adpter.php");			
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
							<h3 class="page-title"> 业务订单管理 <small></small></h3>
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
									<a href="#">
										票房业务管理
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
										<li id="chu_tab" class="active">
											<a href="#tab_2" data-toggle="tab">
												待出库
											</a>
										</li>
										<li id="ru_tab">
											<a href="#tab_2" data-toggle="tab">
												待入库
											</a>
										</li>
										<li id="bei_tab">
											<a href="#tab_6" data-toggle="tab">
												待备货
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
														<th style="width:180px"> 订单信息 </th>
														<th > 客户名称 </th>
														<th style="width:150px"> 预派车号牌 </th>
														<th style="width:180px"> 预计到达时间 </th>
														<th style="width:100px"> 操作人 </th>
														<th style="width:150px"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															<input id="order_search" type="text" class="form-control form-filter" name="keyword_search" placeholder="请输入">
														</td>
														<td><input id="buyername_search" type="text" class="form-control form-filter" name="buyername_search" placeholder="请输入"></td>
														<td></td>
														<td>
															
														</td>
														<td>
															<input id="optname_search" type="text" class="form-control form-filter" name="buyername_search" placeholder="请输入">
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
			<div class="modal fade" id="do_out" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">出库</h4>
						</div>
						<div class="modal-body">
							请填写出库信息
						</div>
						<form action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">送货车牌号码 <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input id="carnumber" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">净重(吨) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="outjing" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">毛重(吨) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="outmao" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">皮重(吨) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="outpi" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">结算类型 <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<select id="outtype" style="width: 300px;" class="form-control  ">																
												<option value="1" >代理商</option>
												<option value="2" >门店</option>
												<option value="3" >小工商</option>																														    	 
											</select>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn green" onclick="doOut()">
							确认
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			
			<div class="modal fade" id="do_cancle" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">取消订单</h4>
						</div>
						<div class="modal-body">
							是否确认取消此订单?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn green" onclick="doCancle()">
							确认
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			
			
			<div class="modal fade" id="do_in" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">入库</h4>
						</div>
						<div class="modal-body">
							请填写入库信息
						</div>
						<form action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">入库车牌号码 <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input id="incarnumber" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">客户存瓶(瓶) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="cun" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">回流瓶空瓶(瓶) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="huiempty" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">回流瓶重瓶(瓶) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="huifull" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">回收瓶(瓶) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="huishou" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">客户称重(吨) <span class="required"> </span> </label>
									<div class="col-md-8">
										<div class="input-icon right">
											<!--<input id="refundMessage" type="text" class="form-control"/>-->
											<input type="text" id="weight" style="width: 300px;" maxlength="120" class="form-control">
										</div>
									</div>
								</div>
								
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn blue" onclick="doIn()">
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
			<script src="assets/scripts/admin/sys_orderlist_yw.js"></script>
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