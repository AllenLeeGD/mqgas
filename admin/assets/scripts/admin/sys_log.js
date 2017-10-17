
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
						"name": "username_search",
						"value": $("#username_search").val()
					},{
						"name": "remark_search",
						"value": $("#remark_search").val()
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
function showOrderDetail(bid,type){
	if(type=='wx'){
		document.location.href="sys_orderlist.php?tag=productadmin&item=1&orderid="+bid;
	}else if(type=="jm"){
		document.location.href="sys_orderlist_hw.php?tag=productadmin&item=5&orderid="+bid;
	}else if(type=="dgs"){
		document.location.href="sys_orderlist_pf.php?tag=productadmin&item=2&orderid="+bid;
	}else if(type=="hsp"){
		document.location.href="sys_orderlist_hsp.php?tag=productadmin&item=3&orderid="+bid;
	}
}
function showMember(mes){
	$("#logdetail").html(mes);
	$("#do_jie").modal('show');
}
$(document).ready(function() {
//	$('#datatable_orders').on('draw.dt', function() {
//		$('#datatable_orders').mergeCell({
//			cols: [0, 4, 5]
//		});
//	});
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	var status = util.getParam('status');
	if(status==1){
		$("#logname").html("订单日志");
	}else if(status==2){
		$("#logname").html("用户组日志");
	}else if(status==3){
		$("#logname").html("参数设定日志");
	}else if(status==4){
		$("#logname").html("内部员工操作日志");
	}else if(status==5){
		$("#logname").html("优惠券日志");
	}else if(status==6){
		$("#logname").html("积分设置日志");
	}  
	if(util.isNullStr(start)) {
		start = 0;
	}
	if(util.isNullStr(params)) {
		ProviderOrder.init("../index.php/Mq/Log/findLogsByStatus/status/"+status, start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		$('#username_search').val(arrval0[0]);
		$('#remark_search').val(arrval1[1]);
		ProviderOrder.init("../index.php/Mq/Log/findLogsByStatus/status/"+status, start);
	}
	
	$("#log_tab").bind('click', function() {			
		ProviderOrder.init("../index.php/Mq/Log/findLogsByStatus/status/"+status, 0);
	});	
});
