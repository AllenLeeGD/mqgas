<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>回访处理</title>
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
							<h3 class="page-title"> 处理回访信息 <small>处理回访信息</small></h3>
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
									<a href="sys_recallopt.php?tag=sysadmin&item=14">
										客户回访处理
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
										处理回访
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
													<div id="form_app" >
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">日期 <span class="required"> </span> </label>
														<div class="col-md-8">
															<label class="control-label">
																<span id="optdate" name="optdate" v-text="sendobj.optdate" class="control-label"></span>
															</label>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
														<label class="control-label col-md-2">门店名称 <span class="required"> </span> </label>
														<div class="col-md-8">
															<label class="control-label">
																<span id="dname" name="dname" v-text="sendobj.dname" ></span>
															</label>
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">回访情况 <span class="required"> </span> </label>
														<div class="col-md-8">	
															<label class="control-label" style="text-align: left;">
																<span id="remark" v-text="sendobj.remark" ></span>	
															</label>																												
														</div>
													</div>
													<div id="vipdiscountgroup" class="form-group">
														<label class="control-label col-md-2">处理结果 <span class="required"> *</span> </label>
														<div class="col-md-8">															
															<textarea id="optremark" v-model="sendobj.optremark" rows="10" cols="50"></textarea>
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
									&nbsp;&nbsp;处理回访&nbsp;&nbsp;
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
		<script src="assets/scripts/admin/sys_recall_addopt.js"></script>
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