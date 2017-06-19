<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>编辑公告</title>
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
				if($_SESSION['admin_type']=="admin"){
					include("common/admin_menu.html");
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
							公告管理 <small>发布最新消息、活动公告等</small>
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
											<a href="sys_informationadd.php?tag=sysadmin&item=2">
												新增公告
											</a>
										</li>
										<li>
											<a href="sys_informationlist.php?tag=sysadmin&item=2">
												公告管理
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
									<a href="sys_informationlist.php?tag=sysadmin&item=2"> 公告管理 </a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									编辑公告
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
											<h3 class="form-section">编辑公告</h3>
											<div id="titlegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">标题
													<span class="required">
													 	*
													</span>
												</label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="titleerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="titlesuc" class="fa fa-check" style="display:none;"></i>
														<input id="title" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="remarkgroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">简述
													<span class="required">
													 	*
													</span>
												</label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="remarkerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="remarksuc" class="fa fa-check" style="display:none;"></i>
														<input id="remark" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="headicongroup" class="form-group">
												<label class="control-label col-md-2">封面图片
												<span class="required">
													 *
												</span>
												</label>
												<div class="col-md-5">
													<input type="file" id="headicon" name="headicon">
													<span id="headiconimg" class="help-block">
														 上传封面图片文件（文件大小不超过2MB）
													</span>
												</div>
											</div>
											<div id="contentgroup" class="form-group">
												<label class="control-label col-md-2">内容
													<span class="required">
														 *
													</span>
												</label>
												<div id="ta" class="col-md-10">
													<textarea id="content" class="form-control" name="content" rows="6"></textarea>
												</div>
											</div>
											<div id="statusgroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">置顶
													<span class="required">
													 	
													</span>
												</label>
												<div class="col-md-4">
													<div class="input-icon right">
														<input id="status" type="text" class="form-control input-inline input-xsmall"/>
														<span class="help-inline">
															 （ 0=不置顶，1=置顶显示在首页 ）
														</span>
													</div>
												</div>
											</div>
											<div id="sortnogroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">排序
													<span class="required">
													 	
													</span>
												</label>
												<div class="col-md-4">
													<div class="input-icon right">
														<input id="sortno" type="text" class="form-control input-inline input-xsmall"/>
														<span class="help-inline">
															 （ 数字越大，排在越前 ）
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-2 col-md-9">
												&nbsp;&nbsp;<button id="btnSave" type="submit" data-loading-text="保存中..." class="btn yellow">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>&nbsp;&nbsp;
												<button id="btnReset" type="button" class="btn default">&nbsp;&nbsp;返回&nbsp;&nbsp;</button>
											</div>
										</div>
										<input id="headicondata" type="hidden">
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
		<script src="assets/scripts/admin/sys_informationedit.js"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<script>
			jQuery(document).ready(function() {
				$(".fancybox-button").live("click", function(event) {
					var href = $(this).attr('href');
					$.fancybox({
						'href': href
					});
					return false;
				});
				App.init();
			});
		</script>
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>