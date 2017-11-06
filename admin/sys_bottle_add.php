<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>客户钢瓶明细</title>
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
							<h3 class="page-title"> 新增客户钢瓶明细 <small>增加客户钢瓶明细信息</small></h3>
							<ul class="page-breadcrumb breadcrumb">
								<!--<li class="btn-group">
								<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
								<span>
								功能菜单
								</span>
								<i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu pull-right" role="menu">
								<li>
								<a href="sys_paramset.html?tag=sysadmin&item=3">
								参数设置
								</a>
								</li>
								</ul>
								</li>-->
								<li>
									<a>
										平台管理
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									<a href="sys_bottle_main.php?tag=sysadmin&item=16">
										客户钢瓶管理
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
								<li class="active">
									<a href="#tab_0" data-toggle="tab">
										添加钢瓶明细信息
									</a>
								</li>
								<!--<li>
								<a href="#tab_1" data-toggle="tab">
								学分奖励
								</a>
								</li>-->
							</ul>
							<!-- BEGIN VALIDATION STATES-->
							<div class="tab-content">
								<div class="tab-pane active" id="tab_0">
									<div class="portlet box grey">
										<div style="border-top:1px solid #999;"></div>
										<div class="portlet-body form">
											<!-- BEGIN FORM-->
											<form action="javascript:;" class="form-horizontal">
												<div class="form-body" style="padding:16px 26px 16px 26px;">
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">日期 <span class="required"> *</span> </label>
														<div class="col-md-8">
															<div class="input-group input-large date date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
																<input id="optdate" name="optdate" type="text" class="form-control" readonly>
																<span class="input-group-btn">
																<button class="btn default" type="button">
																<i class="fa fa-calendar"></i>
																</button> </span>
															</div>
														</div>
													</div>
													<div id="form_app" >
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">客户名称 <span class="required"> </span> </label>
														<div class="col-md-8">
															<label class="control-label">
																<span id="membername" name="membername" v-text="sendobj.membername" class="control-label"></span>
															</label>
														</div>
													</div>
													<input type="hidden" id="membername" name="membername" v-model="sendobj.membername">
													<input type="hidden" id="memberid" name="memberid" v-model="sendobj.memberid">
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">客户电话 <span class="required"> </span> </label>
														<div class="col-md-8">
															<label class="control-label">
																<span id="mobile" name="mobile" v-text="sendobj.mobile" ></span>
															</label>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">门店名称 <span class="required"> *</span> </label>
														<div class="col-md-8">
															<select id="departmentid" name="departmentid" v-model="sendobj.departmentid" class="form-control">																
																<option v-for="option in options" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block"> 例如：吉大店 </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">变动形式 <span class="required"> *</span> </label>
														<div class="col-md-8">
															<select id="changetype" name="changetype" v-model="sendobj.changetype" class="form-control  input-large">																
																<option value="1" >出-瓶换瓶</option>
																<option value="2" >出-门店退瓶到气库</option>
																<option value="3" >出-借瓶阀</option>
																<option value="4" >入-气库发瓶到门店</option>
																<option value="5" >入-收押金</option>
																<option value="6" >入-客户退瓶</option>
																<option value="7" >入-瓶换瓶</option>
																<option value="8" >出-退押金</option>
																<option value="0" >其它</option>							
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">类型 <span class="required"> *</span> </label>
														<div class="col-md-8">
															<select id="type" name="type" v-model="sendobj.type" class="form-control  input-large">																
																<option value="1" >退户瓶</option>
																<option value="2" >还瓶</option>
																<option value="3" >回收杂瓶</option>
																<option value="4" >回流瓶</option>
																<option value="5" >入重瓶</option>
																<option value="6" >借出瓶</option>
																<option value="7" >押金瓶</option>
																<option value="8" >回收杂瓶</option>
																<option value="9" >回流瓶</option>
																<option value="10" >售重瓶</option>
																<option value="0" >其它</option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">收据号码 <span class="required"> </span> </label>
														<div class="col-md-8">
															<input id="receipt" v-model="sendobj.receipt" type="text" class="form-control input-large"/>
															<span class="help-block">  </span>
														</div>
													</div>
													
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">瓶规格 <span class="required"> </span> </label>
														<div class="col-md-8">
															<select id="pid" name="pid" v-model="sendobj.pid" class="form-control  input-large">																
																<option v-for="option in pings" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">接口规格 <span class="required"></span> </label>
														<div class="col-md-8">
															<select id="jid" name="jid" v-model="sendobj.jid" class="form-control  input-large" >																
																<option v-for="option in jies" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">配件规格 <span class="required"> </span> </label>
														<div class="col-md-8">
															<select id="fid" name="fid" v-model="sendobj.fid" class="form-control  input-large">																
																<option v-for="option in fs" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">燃气类型 <span class="required"> </span> </label>
														<div class="col-md-8">
															<select id="rid" name="rid" v-model="sendobj.rid" class="form-control  input-large">																
																<option v-for="option in rans" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">钢瓶类型 <span class="required"> </span> </label>
														<div class="col-md-8">
															<select id="gpid" name="gpid" v-model="sendobj.gpid" class="form-control  input-large">																
																<option v-for="option in gps" :value="option.pkid" v-text="option.name" >  																																    	   </option>
															</select>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">数量 <span class="required"> </span> </label>
														<div class="col-md-8">
															<input id="optnumber" v-model="sendobj.optnumber" type="number" class="form-control input-large"/>
															<span class="help-block">  </span>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">单价 <span class="required"> </span> </label>
														<div class="col-md-8">
															<input id="price" v-model="sendobj.price" type="number" class="form-control input-large"/>
															<span class="help-block">  </span>
														</div>
													</div>
													
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">备注 <span class="required"> </span> </label>
														<div class="col-md-8">
															<input id="remark" v-model="sendobj.remark" type="text" class="form-control input-large"/>
															<span class="help-block">  </span>
														</div>
													</div>
													</div>
												</div>
											</form>
											<!-- END FORM-->
										</div>
									</div>
								</div>
								<div class="col-md-offset-2 col-md-9">
									&nbsp;&nbsp;
									<button id="btnSave" type="submit" data-loading-text="保存中..." class="btn yellow">
									&nbsp;&nbsp;保存&nbsp;&nbsp;
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
		<script src="assets/scripts/admin/sys_bottle_add.js"></script>
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