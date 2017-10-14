$(window).unload(function(){ 
	TV_Disable(); 
}); 	
//离开页面
$(window).bind('beforeunload',function(){
	TV_Disable();
});
function AppendStatus(szStatus)
{
	
}

function openSet(pid,pname,mobile) {
	document.location.href = "sys_orderlist_hwadd.php?tag=productadmin&item=4&memberid="+pid+"&membername="+base64_encode(pname)+"&mobile="+mobile;
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
						"name": "name_search",
						"value": $("#name_search").val()
					},{
						"name": "mobile_search",
						"value": $("#mobile_search").val()
					},{
						"name": "membertype_search",
						"value": $("#membertype_search").val()
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
function  T_GetEvent(uID,uEventType,uHandle,uResult,szdata)
{
	//var vValueArray=qnviccub.QNV_Event(0,2,0,"","",1024);
	if(uEventType == -1)
		return;
	var vValue=" type="+uEventType+" Handle="+uHandle+" Result="+uResult+" szdata="+szdata;
	if(uEventType==BriEvent_GetCallID){
		if(!util.isNullStr(szdata) && szdata.length>=4){
			document.location.href = "sys_orderlist_hwadd.php?tag=productadmin&item=4&mobile="+szdata;
		}
	}
}
function openMemberDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-view");
	util.showLoading();
	openmodal.load('sys_memberdetail.html', '', function() {
		util.getUrl("/Mq/Member/findMemberByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var realname = objinfo[0].realname;
				var mobile = objinfo[0].mobile;
				var levelname = objinfo[0].levelname;
				var address = objinfo[0].address;
				var imgpath = objinfo[0].headicon;
				var membertype = objinfo[0].membertype;
				var detailtype = objinfo[0].detailtype;
				if(!util.isNullStr(imgpath)){
					if(imgpath.indexOf("http") != -1){
						imgpath = "<img src=\"" + imgpath +"\" style=\"height:100px;\">";
					}else{
						imgpath = "<img src=\""+ edu_host + "/Upload/" + imgpath +"\" style=\"height:100px;\">";	
					}					
				}else{
					imgpath = "";
				}
				$('#realname_detail').html(realname);
				$('#mobile_detail').html(mobile);
				$('#levelname_detail').html(levelname);
				$('#address').html(address);
				$('#pdpic_detail').html(imgpath);
				
				$('#code_detail').html(objinfo[0].code);
				$('#storename_detail').html(objinfo[0].storename);
				$('#yewuname_detail').html(objinfo[0].yewuname);
				$('#gangping').html(objinfo[0].gangping);
				$('#anjian').html(objinfo[0].anjian);
				$('#jiage').html(objinfo[0].jiage);
				if(!util.isNullStr(membertype)){
					if(membertype==1){
						$('#membertype_detail').html("居民用户");
					}else if(membertype==2){
						$('#membertype_detail').html("小工商");
					}else if(membertype==3){
						$('#membertype_detail').html("大工商");
					}
				}
				if(!util.isNullStr(detailtype)){
					if(detailtype==1){
						$('#detailtype_detail').html("代理商");
					}else if(detailtype==2){
						$('#detailtype_detail').html("来料加工");
					}else if(detailtype==3){
						$('#detailtype_detail').html("门店");
					}else if(detailtype==4){
						$('#detailtype_detail').html("门店气");
					}else if(detailtype==5){
						$('#detailtype_detail').html("民用气");
					}else if(detailtype==6){
						$('#detailtype_detail').html("直营代理");
					}
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
$(document).ready(function() {
	TV_Initialize();
	getEvent(T_GetEvent);
	$('#datatable_orders').on('draw.dt', function() {
	
	});
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	var mobile = util.getParam("mobile");
	if(!util.isNullStr(mobile)){
		util.errorMsg('此电话有多个用户账号,请选择');
		$("#mobile_search").val(mobile);
		$("#btnSearch").click();
	}
	if(util.isNullStr(start)) {
		start = 0;
	}
	if(util.isNullStr(params)) {
		ProviderOrder.init("../index.php/Mq/Order/findhwmain", start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		$('#name_search').val(arrval0[1]);
		$('#mobile_search').val(arrval1[1]);
		$('#membertype_search').val(arrval1[3]);
		ProviderOrder.init("../index.php/Mq/Order/findhwmain", start);
	}
	
	$("#songqi_tab").bind('click', function() {
		readed = true;
		ProviderOrder.init("../index.php/Mq/Order/findhwmain", start);
	});
});
