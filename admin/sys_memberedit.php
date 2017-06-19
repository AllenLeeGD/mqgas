<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>编辑会员信息</title>
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
							<h3 class="page-title"> 会员资料管理 <small>修改会员资料</small></h3>
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
									<a href="sys_memberlist.php?tag=memberadmin&item=1">
										会员资料管理
									</a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									编辑会员信息
								</li>
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
					<!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-md-12">
							<!-- BEGIN VALIDATION STATES-->
							<div class="portlet box grey">
								<div style="border-top:1px solid #999;"></div>
								<div class="portlet-body form">
									<!-- BEGIN FORM-->
									<form action="javascript:;" class="form-horizontal">
										<div class="form-body" style="padding:16px 26px 16px 26px;">
											<h3 class="form-section">编辑会员信息</h3>
											<div id="realnamegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">真实姓名 <span class="required"> </span> </label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="realnameerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="realnameerr" class="fa fa-check" style="display:none;"></i>
														<input id="realname" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="mobilegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">联系电话 <span class="required"> </span> </label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="mobileerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="mobileerr" class="fa fa-check" style="display:none;"></i>
														<input id="mobile" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="wechatgroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">送货地址 <span class="required"> </span> </label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="addresserr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="addresserr" class="fa fa-check" style="display:none;"></i>
														<input id="address" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="wechatgroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">会员等级 <span class="required"> </span> </label>
												<div class="col-md-5">
													<div class="input-icon right">
														<select id="type" name="channelid" class="form-control">
														
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-2 col-md-9">
												&nbsp;&nbsp;
												<button id="btnSave" type="submit" data-loading-text="保存中..." class="btn yellow">
												&nbsp;&nbsp;保存&nbsp;&nbsp;
												</button>
												&nbsp;&nbsp;
												<button id="btnReset" type="button" class="btn default">
												&nbsp;&nbsp;返回&nbsp;&nbsp;
												</button>
											</div>
										</div>
										<input id="pdpicdata" type="hidden">
									</form>
									<!-- END FORM-->
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
		<script src="assets/scripts/admin/sys_memberedit.js"></script>
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