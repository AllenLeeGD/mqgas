<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>居民小工商订单(门店)</title>
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
							<h3 class="page-title"> 居民小工商订单(门店) <small></small></h3>
							<ul class="page-breadcrumb breadcrumb">
								<li id="btn_group" class="btn-group" style="display: none;">
									<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
									<span>
										功能菜单
									</span>
									<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li id="psk_container" style="display: none;">
											<a href="javascript:psk()">
												批量收款
											</a>
										</li>
										<li id="pck_container" style="display: none;">
											<a href="javascript:pck()">
												批量存款
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
									<a href="sys_orderlist_md.php?tag=productadmin&item=6">
										居民小工商订单(门店)
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
										<li id="fen_tab" class="active">
											<a href="#tab_2" data-toggle="tab">
												待分配配送
											</a>
										</li>
										<li id="shou_tab">
											<a href="#tab_2" data-toggle="tab">
												待收款
											</a>
										</li>
										<li id="cun_tab">
											<a href="#tab_7" data-toggle="tab">
												待存款
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
															<input id="order_search" type="text" class="form-control form-filter" name="order_search" placeholder="请输入">
														</td>
														<td><input id="buyername_search" type="text" class="form-control form-filter" name="buyername_search" placeholder="请输入"></td>
														<td></td>
														<td>
															<input id="mobile_search" type="text" class="form-control form-filter" name="mobile_search" placeholder="请输入">
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
							<h4 class="modal-title green">分配</h4>
						</div>
						<div class="modal-body">
							请选择配送人员或车辆
							<br />
							<span id="buyeraddress"></span>
						</div>
						<form id="form_app" action="javascript:;" class="form-horizontal">
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
			
			
			
			<div class="modal fade" id="do_shou" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">收款</h4>
						</div>
						<div class="modal-body">
							确认送气工或司机已经将款项交付到门店了吗?
						</div>
						<form id="form_app" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">收据号码 <span class="required"> </span> </label>
									<div class="col-md-8">
										<input id="shounumber" style="width: 300px;" maxlength="120" class="form-control" ></textarea>										
										<span class="help-block"> 如果有收据，请填写收据号码 </span>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button type="button" class="btn blue" onclick="doShou()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			
			<div class="modal fade" id="do_plshou" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">批量收款</h4>
						</div>
						<div class="modal-body">
							确认送气工或司机已经将款项交付到门店了吗?
						</div>
						<form id="plform_app" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">收据号码 <span class="required"> </span> </label>
									<div class="col-md-8">
										<input id="plshounumber" style="width: 300px;" maxlength="120" class="form-control" ></textarea>										
										<span class="help-block"> 如果有收据，请填写收据号码 </span>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button type="button" class="btn blue" onclick="doPLShou()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			<div class="modal fade" id="do_jie" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">月结客户</h4>
						</div>
						<div class="modal-body">
							确认此客户为月结客户吗?如果是月结客户，流程将会直接结束。
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button type="button" class="btn blue" onclick="doJie()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			<div class="modal fade" id="do_cun" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">存款</h4>
						</div>
						<div class="modal-body">
							确认已经将订单款项存入银行了吗?
						</div>
						<form id="form_app" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<label class="control-label col-md-4">收款方式 <span class="required">*</span> </label>
									<div class="col-md-8">
										<select id="shoutype" name="shoutype" class="form-control input-middle" style="width: ;">
											<option value="1">中晟公司转账</option>
											<option value="2">转账(码头中行银海支行)</option>
											<option value="3">转账(码头中行分行)</option>
											<option value="4">市区现金(中晟工行丽景支行)</option>
											<option value="5">转账(码头工行分行)</option>
											<option value="6">微付(中晟建行丽景支行)</option>
											<option value="7">现金(码头中行银海支行)</option>
											<option value="8">转账(码头银行扣款)</option>
											<option value="9">优惠券</option>
											<option value="a">科技余气</option>
											<option value="b">码头余气</option>
											<option value="f">中晟公司现金</option>
											<option value="i">转账(中晟工行丽景支行)</option>
											<option value="h">码头公司现金</option>
											<option value="j">中行银海支行梁绮凌</option>
											<option value="l">转账(中晟中行银海支行)</option>
											<option value="m">转账(中晟邮政储蓄支行)</option>
											<option value="n">现金(中晟邮政储蓄支行)</option>
											<option value="o">现金(中晟中行银海支行)</option>
											<option value="p">现金(中晟工行丽景支行)</option>
											<option value="q">市区现金(中晟中行银海支行)</option>
											<option value="r">市区现金(中晟邮政储蓄支行)</option>
											<option value="s">西区现金(中晟中行银海支行)</option>
											<option value="t">西区现金(中晟邮政储蓄支行)</option>
											<option value="u">官塘现金(中晟中行银海支行)</option>
											<option value="v">官塘现金(中晟邮政储蓄支行)</option>
											<option value="w">中转仓现金(中晟中行银海支行)</option>
											<option value="x">中转仓现金(中晟邮政储蓄支行)</option>
											<option value="k">代收代付(码头款)</option>
											<option value="y">刷卡(中晟中行银海支行)</option>
										</select>										
										<span class="help-block"> 请选择 </span>
									</div>
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">银行存款信息及备注 <span class="required"> </span> </label>
									<div class="col-md-8">
										<textarea id="cunmsg" rows="10" style="width: 300px;" maxlength="120" class="form-control" ></textarea>										
										<span class="help-block"> 例如:银行名称,存单号码 </span>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button  type="button" class="btn blue" onclick="doCun()">
							确定
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			
			
			<div class="modal fade" id="do_plcun" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="padding:10px 20px 10px 20px;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title green">批量存款</h4>
						</div>
						<div class="modal-body">
							确认已经将这些订单款项存入银行了吗?
						</div>
						<form id="plform_app" action="javascript:;" class="form-horizontal">
							<div class="form-body" style="padding:16px 26px 16px 26px;">
								<div class="form-group">
									<label class="control-label col-md-4">收款方式 <span class="required">*</span> </label>
									<div class="col-md-8">
										<select id="plshoutype" name="plshoutype" class="form-control input-middle" style="width: ;">
											<option value="1">中晟公司转账</option>
											<option value="2">转账(码头中行银海支行)</option>
											<option value="3">转账(码头中行分行)</option>
											<option value="4">市区现金(中晟工行丽景支行)</option>
											<option value="5">转账(码头工行分行)</option>
											<option value="6">微付(中晟建行丽景支行)</option>
											<option value="7">现金(码头中行银海支行)</option>
											<option value="8">转账(码头银行扣款)</option>
											<option value="9">优惠券</option>
											<option value="a">科技余气</option>
											<option value="b">码头余气</option>
											<option value="f">中晟公司现金</option>
											<option value="i">转账(中晟工行丽景支行)</option>
											<option value="h">码头公司现金</option>
											<option value="j">中行银海支行梁绮凌</option>
											<option value="l">转账(中晟中行银海支行)</option>
											<option value="m">转账(中晟邮政储蓄支行)</option>
											<option value="n">现金(中晟邮政储蓄支行)</option>
											<option value="o">现金(中晟中行银海支行)</option>
											<option value="p">现金(中晟工行丽景支行)</option>
											<option value="q">市区现金(中晟中行银海支行)</option>
											<option value="r">市区现金(中晟邮政储蓄支行)</option>
											<option value="s">西区现金(中晟中行银海支行)</option>
											<option value="t">西区现金(中晟邮政储蓄支行)</option>
											<option value="u">官塘现金(中晟中行银海支行)</option>
											<option value="v">官塘现金(中晟邮政储蓄支行)</option>
											<option value="w">中转仓现金(中晟中行银海支行)</option>
											<option value="x">中转仓现金(中晟邮政储蓄支行)</option>
											<option value="k">代收代付(码头款)</option>
											<option value="y">刷卡(中晟中行银海支行)</option>
										</select>										
										<span class="help-block"> 请选择 </span>
									</div>
									<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
									<label class="control-label col-md-4">银行存款信息及备注 <span class="required"> </span> </label>
									<div class="col-md-8">
										<textarea id="plcunmsg" rows="10" style="width: 300px;" maxlength="120" class="form-control" ></textarea>										
										<span class="help-block"> 例如:银行名称,存单号码 </span>
									</div>
								</div>
							</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">
							取消
							</button>
							<button  type="button" class="btn blue" onclick="doPLCun()">
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
			<script src="assets/scripts/admin/sys_orderlist_md.js"></script>
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