<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>添加管理员</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
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
							添加管理员 <small>按您的条件添加管理员</small>
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
											<a href="adminadd.php?tag=sysadmin&item=30">
												添加管理员
											</a>
										</li>
										<li>
											<a href="adminlist.php?tag=sysadmin&item=30">
												管理员设置
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
									<a href="adminadd.php?tag=sysadmin&item=30"> 添加管理员 </a>
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
								<div style="border-top:1px solid #999;">
								</div>
								<div class="portlet-body form">
									<!-- BEGIN FORM-->
									<form action="javascript:;" class="form-horizontal">
										<div class="form-body" style="padding:16px 26px 16px 26px;">
											<h3 class="form-section">添加管理员</h3>
											<div id="costvaluegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-4">账户名称(英文和数字)
													<span class="required">
													 	*
													</span>
												</label>
												<div class="col-md-6">
													<div class="input-icon right">
														<input id="username" type="text" class="form-control input-inline" style="width: 100%;"/>
														<span class="help-inline">
															 
														</span>
													</div>
												</div>
											</div>
											<div id="qtygroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-4">账户初始密码
													<span class="required">
													 	*
													</span>
												</label>
												<div class="col-md-6">
													<div class="input-icon right">
														<input id="password" type="text" class="form-control input-inline" style="width: 100%;"/>
														<span class="help-inline">
															
														</span>
													</div>
												</div>
											</div>
											<div id="typegroup" class="form-group">
												<label class="control-label col-md-4">管理员类型
												<span class="required">
													 *
												</span>
												</label>
												<div class="col-md-6">
													<select id="type" name="channelid" class="form-control">
														<option value="">请选择...</option>
														<option value="2">高级管理员</option>
														<option value="3">普通管理员</option>
														<option value="4">财务人员</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-2 col-md-9">
												&nbsp;&nbsp;<button id="btnSave" type="submit" data-loading-text="添加中..." class="btn yellow">&nbsp;&nbsp;添加&nbsp;&nbsp;</button>&nbsp;&nbsp;
												<button id="btnReset" type="button" class="btn default">&nbsp;&nbsp;重置&nbsp;&nbsp;</button>
											</div>
										</div>
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
		<script src="assets/scripts/admin/adminadd.js"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<script>
			jQuery(document).ready(function() {
				App.init();
			});
		</script>
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>