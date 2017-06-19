<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>新增资讯分类</title>
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
							资讯分类管理 <small>新增、删除分类</small>
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
											<a href="sys_informationadd.php?tag=sysadmin&item=16">
												新增资讯
											</a>
										</li>
										<li>
											<a href="sys_informationlist.php?tag=sysadmin&item=16">
												头条资讯管理
											</a>
										</li>
										<li>
											<a href="sys_informationtypeadd.php?tag=sysadmin&item=16">
												新增资讯分类
											</a>
										</li>
										<li>
											<a href="sys_informationtypelist.php?tag=sysadmin&item=16">
												资讯分类管理
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
									<a href="sys_informationtypelist.php?tag=sysadmin&item=16"> 资讯分类管理 </a>
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
											<h3 class="form-section">新增资讯分类</h3>
											<div id="name1group" class="form-group">
												<label class="control-label col-md-2">分类名称
												<span class="required">
													 
												</span>
												</label>
												<div class="col-md-4">
													<input id="name1" type="text" class="form-control input-inline input-small"/>
												</div>
											</div>
											
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-2 col-md-9">
												&nbsp;&nbsp;<button id="btnSave1" data-loading-text="保存中..." class="btn yellow input-inline" type="button">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
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
		<script src="assets/scripts/custom/ajaxfileupload.js"></script>
		<script src="assets/scripts/admin/sys_informationtypeadd.js"></script>
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