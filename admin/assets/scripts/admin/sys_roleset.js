function openDelConfirm(pkid, index,status) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#view_data").data("status", status);
	$("#do_delWorker").modal('show');
}

function openResetConfirm(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#do_reset").modal('show');
}

function doDelWorker() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var istatus = $("#view_data").data("status");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Role/delworker/pkid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('删除成功');
				$("#do_delWorker").modal('hide');
			} else {
				util.errorMsg('删除失败');
			}
			ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/"+istatus, index);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doReset() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Role/resetworker/pkid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('重置成功');
				$("#do_reset").modal('hide');
			} else {
				util.errorMsg('重置失败');
			}
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function openEdit(pid) {
	document.location.href = "sys_roleset_edit.php?tag=sysadmin&item=4&pid="+pid;
}

var ProviderOrder = function() {

	var initPickers = function() {
		//init date pickers
		$('.date-picker').datepicker({
			rtl: App.isRTL(),
			autoclose: true
		});
	}

	var handleOrder = function(url, startindex) {
		if($('#datatable_orders').hasClass('dataTable')) {
			var dttable = $('#datatable_orders').dataTable();
			dttable.fnClearTable(); //清空一下table
			dttable.fnDestroy(); //还原初始化了的datatable
		}

		var grid = new Datatable();
		grid.init({
			src: $("#datatable_orders"),
			onSuccess: function(grid) {
				// execute some code after table records loaded
			},
			onError: function(grid) {
				// execute some code on network or other general error  
			},
			dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
				"aLengthMenu": [
					[20, 30],
					[20, 30] // change per page values here
				],
				"iDisplayStart": startindex,
				"iDisplayLength": 20, // default record count per page
				"bServerSide": true, // server side processing
				"sAjaxSource": url, // ajax source
				"bSort": false, //不允许排序
				"aaSorting": [
					[1, "asc"]
				], // set first column as a default sort by asc
				"fnServerParams": function(aoData) {
					aoData.push({
						"name": "realname_search",
						"value": $("#realname_search").val()
					}, {
						"name": "mobile_search",
						"value": $("#mobile_search").val()
					}, {
						"name": "name_search",
						"value": $("#name_search").val()
					});
				}
			}
		});

		// handle group actionsubmit button click
		grid.getTableWrapper().on('click', '.table-group-action-submit', function(e) {
			e.preventDefault();
			var action = $(".table-group-action-input", grid.getTableWrapper());
			if(action.val() != "" && grid.getSelectedRowsCount() > 0) {
				grid.addAjaxParam("sAction", "group_action");
				grid.addAjaxParam("sGroupActionName", action.val());
				var records = grid.getSelectedRows();
				for(var i in records) {
					grid.addAjaxParam(records[i]["name"], records[i]["value"]);
				}
				grid.getDataTable().fnDraw();
				grid.clearAjaxParams();
			} else if(action.val() == "") {
				App.alert({
					type: 'danger',
					icon: 'warning',
					message: '请选择您要执行的操作',
					container: grid.getTableWrapper(),
					place: 'prepend'
				});
			} else if(action.val() != "Excel" && grid.getSelectedRowsCount() === 0) {
				App.alert({
					type: 'danger',
					icon: 'warning',
					message: '请勾选要操作的记录',
					container: grid.getTableWrapper(),
					place: 'prepend'
				});
			} else {

			}
		});
	};

	var initComponents = function() {
		//init datepickers
		/*$('.date-picker').datepicker({
		    rtl: App.isRTL(),
		    autoclose: true
		});

		//init datetimepickers
		$(".datetime-picker").datetimepicker({
		    isRTL: App.isRTL(),
		    autoclose: true,
		    todayBtn: true,
		    pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		    minuteStep: 10
		});*/

		//init maxlength handler
		/*$('.maxlength-handler').maxlength({
			limitReachedClass: "label label-danger",
			alwaysShow: true,
			threshold: 5
		});*/

		// slider 2
		/*$('#slider_2').noUiSlider({
		         range: [0,1000]
		        ,start: [10,30]
		        ,handles: 2
		        ,connect: true
		        ,step: 1
		        ,serialization: {
		             to: [$('#slider_2_input_start'), $('#slider_2_input_end')]
		            ,resolution: 1
		    }
		});*/
	};

	return {

		//main function to initiate the module
		init: function(url, startindex) {
			initComponents();
			//handleValidation1();
			handleOrder(url, startindex);
		}

	};

}();
var readed = false;

$(document).ready(function() {
	$('#datatable_orders').on('draw.dt', function() {
		$('#datatable_orders').mergeCell({
			cols: [0, 4, 5, 6]
		});
	});
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)) {
		start = 0;
	}
	if(util.isNullStr(params)) {
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/huawu", start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		$('#realname_search').val(arrval0[1]);
		$('#mobile_search').val(arrval1[1]);
		$('#name_search').val(arrval1[2]);
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/huawu", start);
	}
	
	$("#huawu_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/huawu", 0);
	});
	
	$("#guanli_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/guanli", 0);
	});
	
	$("#biz_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/biz", 0);
	});
	
	$("#caiwu_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/caiwu", 0);
	});
	
	$("#piaofang_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/piaofang", 0);
	});
	
	$("#siji_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/siji", 0);
	});
	
	$("#songqi_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/songqi", 0);
	});
	
	$("#yayun_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/yayun", 0);
	});
	
	$("#yingye_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/yingye", 0);
	});
	$("#chedui_tab").bind('click', function() {
		readed = true;
		document.title="员工管理";
		ProviderOrder.init("../index.php/Mq/Role/findRoleByStatus/status/chedui", 0);
	});
});
