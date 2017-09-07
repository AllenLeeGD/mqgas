<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>添加客户订气订单</title>
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
							<h3 class="page-title"> 新增客户订单信息 <small>增加客户订单信息</small></h3>
							<ul class="page-breadcrumb breadcrumb">
								<li id="detail_btn" class="btn-group" style="display: none;">
									<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
									<span>
										功能菜单
									</span>
									<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="javascript:showInput('15jf','15KG角阀瓶')">
												新增15KG角阀瓶
											</a>
										</li>
										<li>
											<a href="javascript:showInput('15zf','15KG直阀瓶')">
												新增15KG直阀瓶
											</a>
										</li>
										<li>
											<a href="javascript:showInput('50qx','50KG气相瓶')">
												新增50KG气相瓶
											</a>
										</li>
										<li>
											<a href="javascript:showInput('50yx','50KG液相瓶')">
												新增50KG液相瓶
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a>
										平台管理
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_orderlist_hwmain.php?tag=productadmin&item=4">
										话务新增订单
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="#">
										填写客户订单信息
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
							<ul class="nav nav-tabs">
								<li id="information_tab" class="active">
									<a href="#tab_0" data-toggle="tab">
										客户订单信息
									</a>
								</li>
								<li id="detail_tab">
									<a href="#tab_1" data-toggle="tab">
										订单详情
									</a>
								</li>
								<li id="orders_tab">
									<a href="#tab_1" data-toggle="tab">
										客户最近的交易记录
									</a>
								</li>
							</ul>
							<!-- BEGIN VALIDATION STATES-->
							<div class="tab-content">
								<div class="tab-pane active" id="tab_0">
									<div id="information_div" class="portlet box grey">
										<div style="border-top:1px solid #999;"></div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											<form action="javascript:;" class="form-horizontal">
												<div class="form-body" style="padding:16px 26px 16px 26px;">
													<div id="form_app" >
														<div id="vipdiscountgroup" class="form-group">
															<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
															<label class="control-label col-md-2">客户名称 <span class="required"> </span> </label>
															<div class="col-md-8">
																<input id="realname" v-model="sendobj.membername" type="text" class="form-control input-large"/>
																<span class="help-block">  </span>
															</div>
														</div>
														<input type="hidden" id="memberid" v-model="sendobj.memberid">
														<div id="vipdiscountgroup" class="form-group">
															<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
															<label class="control-label col-md-2">客户电话 <span class="required"> </span> </label>
															<div class="col-md-8">
																<input id="mobile" v-model="sendobj.mobile" type="text" class="form-control input-large"/>
																<span class="help-block">  </span>
															</div>
														</div>
														<div id="vipdiscountgroup" class="form-group">
															<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
															<label class="control-label col-md-2">客户地址 <span class="required"> </span> </label>
															<div class="col-md-8">
																<input id="address" v-model="sendobj.address" type="text" class="form-control input-large"/>
																<span class="help-block"></span>
															</div>
														</div>
														<div id="vipdiscountgroup" class="form-group">
															<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
															<label class="control-label col-md-2">备注 <span class="required"> </span> </label>
															<div class="col-md-8">
																<textarea id="remark" v-model="sendobj.remark" rows="10" class="form-control input-large">
																</textarea>
																<span class="help-block"> </span>
															</div>
														</div>
													</div>
												</div>
											</form>
											<!-- END FORM-->
										</div>
									</div>
									
									
									<div id="orders_div" class="portlet-body" style="display: none;">
										<div class="table-container">

											<table class="table table-bordered" id="datatable_orders">
												<thead>
													<tr role="row" class="heading">
														<th style="width:15%"> 订单号 </th>
														<th style="width:8%"> 总价 </th>
														<th style="width:10%"> 下单时间 </th>
														<th style="width:14%"> 电话 </th>
														<th> 地址 </th>
														<th style="width:10%"> 状态 </th>
														<th style="width:8%"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															
														</td>
														<td></td>
														<td></td>
														<td>
															<input id="mobile_search" type="text" class="form-control form-filter" name="mobile_search" placeholder="请输入">
														</td>
														<td>
															<input id="address_search" type="text" class="form-control form-filter" name="address_search" placeholder="请输入">
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
																<option value="8">已完成</option>
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
									
									<div id="details_div" class="portlet-body" style="display: none;">
										<div class="table-container">

											<table class="table table-bordered" id="datatable_details">
												<thead>
													<tr role="row" class="heading">
														<th style="width:15%"> 商品名称 </th>
														<th style="width:8%"> 数量 </th>
														<th style="width:10%"> 单价 </th>
														<th style="width:14%"> 规格 </th>
														<th style="width:8%"> 操作 </th>
													</tr>
													
												</thead>
												<tbody id="detailBody">
													
												</tbody>
											</table>
										</div>
									</div>
									
									
								</div>
								<div id="ajax-modal" class="modal fade" tabindex="-1"></div>
								<div id="modelparam"></div>
								<div class="modal fade" id="doinput" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content" style="padding:10px 20px 10px 20px;">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h4 id="inputtitle" class="modal-title green"></h4>
											</div>
											<div class="modal-body">
												请填写数量
											</div>
											<form action="javascript:;" class="form-horizontal">
												<div class="form-body" style="padding:16px 26px 16px 26px;">
													<div class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-4">数量 <span class="required"> </span> </label>
														<div class="col-md-8">
															<div class="input-icon right">
																<!--<input id="refundMessage" type="text" class="form-control"/>-->
																<input id="productcount" style="width: 300px;" maxlength="120" class="form-control">
															</div>
														</div>
													</div>
												</div>
											</form>
											<div class="modal-footer">
												<button type="button" class="btn default" data-dismiss="modal">
												取消
												</button>
												<button id="confirm_send_btn" type="button" class="btn blue" onclick="doInput()">
												确定
												</button>
											</div>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
								<div id="btndiv" class="col-md-offset-2 col-md-9">
									&nbsp;&nbsp;
									<button id="btnSave" type="submit" data-loading-text="保存中..." class="btn yellow">
									&nbsp;&nbsp;暂存&nbsp;&nbsp;
									</button>
									&nbsp;&nbsp;
									<button id="btnStart" type="submit" data-loading-text="保存中..." class="btn blue">
									&nbsp;&nbsp;发起流程&nbsp;&nbsp;
									</button>
								</div>
							</div>

							<!-- END VALIDATION STATES-->
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
		<script src="assets/scripts/core/datatable.js"></script>
		<script src="assets/scripts/custom/ajaxfileupload.js"></script>
		<script type="text/javascript" src="assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="assets/scripts/custom/vue.min.js"></script>
		<script src="assets/scripts/admin/sys_orderlist_hwadd.js"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
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