<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>居民小工商订单</title>
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
							<h3 class="page-title"> 居民小工商订单 <small></small></h3>
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
									<a href="sys_orderlist_hw.php?tag=productadmin&item=5">
										居民小工商订单
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
										<li id="zan_tab" class="active">
											<a href="#zan_tab" data-toggle="tab">
												暂存
											</a>
										</li>
										<li id="fen_tab">
											<a href="#fen_tab" data-toggle="tab">
												待分派片区
											</a>
										</li>
										<li id="pei_tab">
											<a href="#pei_tab" data-toggle="tab">
												待分配配送
											</a>
										</li>
										<li id="sending_tab">
											<a href="#sending_tab" data-toggle="tab">
												配送中
											</a>
										</li>
										<li id="arrive_tab">
											<a href="#arrive_tab" data-toggle="tab">
												已送达
											</a>
										</li>
										<li id="complete_tab">
											<a href="#complete_tab" data-toggle="tab">
												其他状态
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
														<th style="width:150px"> 地址 </th>
														<th style="width:180px"> 联系电话 </th>
														<th style="width:100px"> 订货金额 </th>
														<th style="width:150px"> 操作 </th>
													</tr>
													<tr role="row">
														<td>
															<input id="order_search" type="text" class="form-control form-filter" name="order_search" placeholder="订单号或配送信息">
														</td>
														<td><input id="buyername_search" type="text" class="form-control form-filter" name="buyername_search" placeholder="请输入"></td>
														<td></td>
														<td>
															<input id="mobile_search" type="text" class="form-control form-filter" name="mobile_search" placeholder="电话或日期">
														</td>
														<td>
															
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
			<div class="modal fade" id="do_fen" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">分派</h4>
						</div>
						<div class="modal-body">
							请根据客户地址分派片区
							<br />
							<span id="buyeraddress"></span>
						</div>
						<form id="form_app" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">门店 <span class="required"> </span> </label>
									<div class="col-md-8">
										<select id="did" name="did" v-model="sendobj.did" class="form-control"  @change="getpianqu">																
											<option v-for="option in departments" :value="option.pkid" v-text="option.name" >  																																    	   </option>
										</select>
										<span class="help-block"> 例如：吉大店 </span>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">片区 <span class="required"> </span> </label>
									<div class="col-md-8">
										<select id="pid" name="pid" v-model="sendobj.pid" class="form-control input-large">																
											<option v-for="option in pianqus" :value="option.pkid" v-text="option.pname" >  																																    	   </option>
										</select>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn green" onclick="doFen()">
							确认
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			<div class="modal fade" id="do_pei" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">分配</h4>
						</div>
						<div class="modal-body">
							请选择配送人员或车辆
							<br />
							<span id="buyeraddress"></span>
						</div>
						<form id="form_app_pei" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">送气工 <span class="required"> </span> </label>
									<div class="col-md-8">
										<select id="songqiid" name="songqiid" v-model="sendobj.songqiid" class="form-control" >																
											<option v-for="option in songqiids" :value="option.sid" v-text="option.sname" >  																																    	   </option>
										</select>
										<span class="help-block">  </span>
									</div>
								</div>
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">车辆 <span class="required"> </span> </label>
									<div class="col-md-8">
										<select id="carid" name="carid" v-model="sendobj.carid" class="form-control input-large">																
											<option v-for="option in carids" :value="option.carid" v-text="option.carnumber" >  																																    	   </option>
										</select>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button id="confirm_send_btn" type="button" class="btn green" onclick="doPei()">
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
							此操作无法恢复，确认要取消订单吗?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button type="button" class="btn blue" onclick="doCancle()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			<div class="modal fade" id="do_send" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">发送订单</h4>
						</div>
						<div class="modal-body">
							确认要发送订单吗?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button  type="button" class="btn blue" onclick="doSend()">
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
			<script type="text/javascript" src="assets/scripts/custom/vue.min.js"></script>
			<script src="assets/scripts/admin/sys_orderlist_hw.js"></script>
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