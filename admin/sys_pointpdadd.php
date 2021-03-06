<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>新增服务项目</title>
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
							服务项目 <small>新增、修改、删除服务项目</small>
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
											<a href="sys_pointpdadd.php?tag=productadmin&item=0">
												新增服务项目
											</a>
										</li>
										<li>
											<a href="sys_pointpdlist.php?tag=productadmin&item=0">
												服务项目
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
									<a href="sys_pointpdlist.php?tag=productadmin&item=0"> 服务项目 </a>
									<i class="fa fa-angle-right"></i>
								</li>
								<li>
									新增服务项目
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
											<h3 class="form-section">新增服务项目</h3>
											<div id="typeidgroup" class="form-group">
												<label class="control-label col-md-2">服务分类
												<span class="required">
													 *
												</span>
												</label>
												<div class="col-md-2">
													<select id="typeid" name="typeid" class="form-control">
														<option value="">请选择...</option>
													</select>
												</div>
											</div>
											<div id="pdnamegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">项目名称
													<span class="required">
													 	*
													</span>
												</label>
												<div class="col-md-5">
													<div class="input-icon right">
														<i id="pdnameerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="pdnamesuc" class="fa fa-check" style="display:none;"></i>
														<input id="pdname" type="text" class="form-control"/>
													</div>
												</div>
											</div>
											<div id="pdpicgroup" class="form-group">
												<label class="control-label col-md-2">封面图片
												<span class="required">
													 *
												</span>
												</label>
												<div class="col-md-5">
													<input type="file" id="pdpic" name="pdpic">
													<span id="pdpicimg" class="help-block">
														 上传图片文件（文件大小不超过2MB）
													</span>
												</div>
											</div>
											<div id="remarksgroup" class="form-group">
												<label class="control-label col-md-2">内容
													<span class="required">
														 *
													</span>
												</label>
												<div id="ta" class="col-md-10">
													<textarea id="remarks" class="ckeditor form-control" name="remarks" rows="6"></textarea>
												</div>
											</div>
											<div id="generalpricegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">普通会员价
													<span class="required">
														 *
													</span>
												</label>
												<div class="col-md-6">
													<div class="input-icon right">
														<i id="generalpriceerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="generalpricesuc" class="fa fa-check" style="display:none;"></i>
														<input id="generalprice" type="text" class="form-control input-xsmall"/>
													</div>
												</div>
											</div>
											<div id="vippricegroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">尊贵会员价
													<span class="required">
													 	
													</span>
												</label>
												<div class="col-md-6">
													<div class="input-icon right">
														<i id="vippriceerr" class="fa fa-exclamation" style="display:none;"></i>
														<i id="vippricesuc" class="fa fa-check" style="display:none;"></i>
														<input id="vipprice" type="text" class="form-control input-inline input-xsmall"/>
														<span class="help-inline">
															 （ 如果不输此项，将按系统设定的折扣自动计算 ）
														</span>
													</div>
												</div>
											</div>
											<div id="standardgroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">规格
													<span class="required">
													 	
													</span>
												</label>
												<div class="col-md-4">
													<div class="input-icon right">
														<input id="standard" type="text" class="form-control input-inline input-large"/>
														<span class="help-inline">
															 （ 如：酒水的700ml就是规格 ）
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
											<div id="updowntaggroup" class="form-group">
												<!--如果录入不正确加入has-success,has-error,has-warning样式，fa-warning-->
												<label class="control-label col-md-2">上下价
													<span class="required">
													 	
													</span>
												</label>
												<div class="col-md-4">
													<div class="input-icon right">
														<input id="updowntag" type="checkbox" class="form-control input-inline input-xsmall"/>
														<span class="help-inline">
															 （ 选中表示上架 ）
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-2 col-md-9">
												<input id="pdpicdata" type="hidden">
												&nbsp;&nbsp;<button id="btnSave" type="submit" data-loading-text="保存中..." class="btn yellow">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>&nbsp;&nbsp;
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
		<script src="assets/scripts/admin/sys_pointpdadd.js"></script>
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