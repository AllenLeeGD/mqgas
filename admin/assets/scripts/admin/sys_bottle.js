function openDelConfirm(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#do_delWorker").modal('show');
}
function showDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-view");
	util.showLoading();
	openmodal.load('sys_bottlerdetail.html', '', function() {
		util.getUrl("/Mq/Bottle/loadbottle/pkid/" + pkid, function(data, status) {
			try {
				$('#membername_detail').html(data.membername);
				$('#mobile_detail').html(data.mobile);
				$('#departmentname_detail').html(data.deparmentname);
				$('#receipt_detail').html(data.receipt);
				$('#pname_detail').html(data.pname);
				$('#jname_detail').html(data.jname);
				$('#fname_detail').html(data.fname);
				$('#rname_detail').html(data.rname);
				$('#gpname_detail').html(data.gpname);
				$('#optnumber_detail').html(data.optnumber);
				$('#price_detail').html(data.price);
				$('#remark_detail').html(data.remark);
				
				if(data.changetype==1){
					$('#changetype_detail').html("出-瓶换瓶");
				}else if(data.changetype==2){
					$('#changetype_detail').html("出-门店退瓶到气库");
				}else if(data.changetype==3){
					$('#changetype_detail').html("出-借瓶阀");
				}else if(data.changetype==4){
					$('#changetype_detail').html("入-气库发瓶到门店");
				}else if(data.changetype==5){
					$('#changetype_detail').html("入-收押金");
				}else if(data.changetype==6){
					$('#changetype_detail').html("入-客户退瓶");
				}else if(data.changetype==7){
					$('#changetype_detail').html("入-瓶换瓶");
				}else if(data.changetype==8){
					$('#changetype_detail').html("出-退押金");
				}else if(data.changetype==0){
					$('#changetype_detail').html("其它");
				}
				
				
				if(data.type==1){
					$('#type_detail').html("退户瓶");
				}else if(data.type==2){
					$('#type_detail').html("还瓶");
				}else if(data.type==3){
					$('#type_detail').html("回收杂瓶");
				}else if(data.type==4){
					$('#type_detail').html("回流瓶");
				}else if(data.type==5){
					$('#type_detail').html("入重瓶");
				}else if(data.type==6){
					$('#type_detail').html("借出瓶");
				}else if(data.type==7){
					$('#type_detail').html("押金瓶");
				}else if(data.type==8){
					$('#type_detail').html("回收杂瓶");
				}else if(data.type==9){
					$('#type_detail').html("回流瓶");
				}else if(data.type==10){
					$('#type_detail').html("售重瓶");
				}else if(data.type==0){
					$('#type_detail').html("其它");
				}
			} catch (err) {
				util.errorMsg('找不到该记录');
			} finally {
				util.hideLoading();
				openmodal.modal('show');
			}
		}, function() {
			util.hideLoading();
			util.errorMsg('内部服务器错误');
			openmodal.modal('hide');
		});
	});
}
function doDelWorker() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Bottle/delbottle/pkid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('删除成功');
				$("#do_delWorker").modal('hide');
			} else {
				util.errorMsg('删除失败');
			}
			document.location.reload();
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function openEdit(pid) {
	document.location.href = "sys_bottle_edit.php?tag=sysadmin&item=16&pkid="+pid;
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
						"name": "optdate_search",
						"value": $("#optdate_search").val()
					},{
						"name": "pname_search",
						"value": $("#pname_search").val()
					},{
						"name": "fname_search",
						"value": $("#fname_search").val()
					},{
						"name": "type_search",
						"value": $("#type_search").val()
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
		
	});
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	var memberid = util.getParam("memberid");
	var membername = util.getParam("membername");
	var mobile = util.getParam("mobile");
	$("#membername").html(base64_decode(membername));
	$("#addBtn").attr("href","sys_bottle_add.php?tag=sysadmin&item=16&memberid="+memberid+"&membername="+membername+"&mobile="+mobile);
	if(util.isNullStr(start)) {
		start = 0;
	}
	if(util.isNullStr(params)) {
		ProviderOrder.init("../index.php/Mq/Bottle/findBottle/memberid/"+memberid, start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		var arrval3 = arrparam[3].split(':');
		$('#optdate_search').val(arrval0[1]);
		$('#pname_search').val(arrval1[1]);
		$('#fname_search').val(arrval2[1]);
		$('#type_search').val(arrval3[1]);
		ProviderOrder.init("../index.php/Mq/Bottle/findBottle/memberid/"+memberid, start);
	}
	
	$("#songqi_tab").bind('click', function() {
		readed = true;
		ProviderOrder.init("../index.php/Mq/Bottle/findBottle/memberid/"+memberid, start);
	});
});
