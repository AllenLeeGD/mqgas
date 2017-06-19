var ProductAdd = function() {
	
	var handleOrder = function(url,startindex,status){
		if ($('#datatable_orders1').hasClass('dataTable')) {
			var dttable = $('#datatable_orders1').dataTable();
			dttable.fnClearTable(); //清空一下table
			dttable.fnDestroy(); //还原初始化了的datatable
		}
		
		var grid = new Datatable();
            grid.init({
                src: $("#datatable_orders1"),
                onSuccess: function(grid) {
                    // execute some code after table records loaded
                },
                onError: function(grid) {
                    // execute some code on network or other general error  
                },
                dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
                    "aLengthMenu": [
                        [20, 30],
                        [20, 30] // change per page values here
                    ],
                    "iDisplayStart": startindex,
                    "iDisplayLength": 20, // default record count per page
                    "bServerSide": true, // server side processing
                    "sAjaxSource": "../index.php/Admin/ProductPoint/findProductPointOrder/status/"+status, // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]], // set first column as a default sort by asc
                    "fnServerParams" : function (aoData) {
					aoData.push(
						{ "name": "keyword_search", "value": $("#keyword_search").val() }
						);
					}
                }
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function(e){
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.addAjaxParam("sAction", "group_action");
                    grid.addAjaxParam("sGroupActionName", action.val());
                    var records = grid.getSelectedRows();
                    for (var i in records) {
                        grid.addAjaxParam(records[i]["name"], records[i]["value"]);    
                    }
                    grid.getDataTable().fnDraw();
                    grid.clearAjaxParams();
                } else if (action.val() == "") {
                    App.alert({type: 'danger', icon: 'warning', message: '请选择您要执行的操作', container: grid.getTableWrapper(), place: 'prepend'});
                } else if (action.val() != "Excel" && grid.getSelectedRowsCount() === 0) {
                    App.alert({type: 'danger', icon: 'warning', message: '请勾选要操作的记录', container: grid.getTableWrapper(), place: 'prepend'});
                } else{
                    
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
		init: function(url,startindex,status) {
			initComponents();
			//handleValidation1();
			handleOrder(url,startindex,status);
		}

	};

}();
function openProductPointDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-view");
	util.showLoading();
	openmodal.load('sys_pointpddetail.html', '', function() {
		util.getUrl("/Admin/ProductPoint/findProductPointByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var pdname = objinfo[0].pdname;
				var studypoint = objinfo[0].studypoint;
				var pdpic = objinfo[0].pdpic;
				if(!util.isNullStr(pdpic)){
					pdpic = "<img src=\""+ edu_host + "/Upload/" + pdpic +"\" style=\"height:100px;\">";
				}else{
					pdpic = "";
				}
				var remarks = objinfo[0].remarks;
				var updowntag = objinfo[0].updowntag;
				var sortno = objinfo[0].sortno;
				var addtime = objinfo[0].addtime;
				if(updowntag==0){
					updowntag = "下架";
				}else if(updowntag==1){
					updowntag = "上架";
				}
				$('#pdname_detail').html(pdname);
				$('#studypoint_detail').html(studypoint);
				$('#pdpic_detail').html(pdpic);
				$('#remarks_detail').html(remarks);
				$('#updowntag_detail').html(updowntag);
				$('#addtime_detail').html(addtime);
				$('#sortno_detail').html(sortno);
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
function openProductPointOrderDetail(orderid) {
	var util = new Util();
	var openmodal = $("#ajax-vieworder");
	util.showLoading();
	openmodal.load('sys_pointpdorderdetail.html', '', function() {
		util.getUrl("/Admin/ProductPoint/findProductPointOrderByOrderid/orderid/" + orderid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var orderid = objinfo[0].orderid;
				var orderdate = objinfo[0].orderdate;
				var pdname = objinfo[0].pdname;
				var costpoint = objinfo[0].costpoint;
				var contactperson = objinfo[0].contactperson;
				var contactel = objinfo[0].contactel;
				var expressno = objinfo[0].expressno;
				var expressname = objinfo[0].expressname;
				var address = objinfo[0].address;
				var pdpic = objinfo[0].pdpic;
				var status = objinfo[0].status;
				if(!util.isNullStr(pdpic)){
					pdpic = "<img src=\""+ edu_host + "/Upload/" + pdpic +"\" style=\"height:100px;\">";
				}else{
					pdpic = "";
				}
				var statusstr = "";
				if(status==0){
					statusstr = "待发货";
				}else if(status==1){
					statusstr = "已发货";
				}else if(status==2){
					statusstr = "已撤销";
				}
				$('#orderid_order').html(orderid);
				$('#orderdate_order').html(orderdate);
				$('#pdname_order').html(pdname);
				$('#costpoint_order').html(costpoint);
				$('#contactperson_order').html(contactperson);
				$('#contactel_order').html(contactel);
				$('#address_order').html(address);
				$('#expressno_order').html(expressno);
				$('#expressname_order').html(expressname);
				$('#status_order').html(statusstr);
				$('#pdpic_order').html(pdpic);
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
function openProductPointOrderDel(orderid,index) {
	$("#view_data").data("delorderid", orderid);
	$("#view_data").data("delindex", index);
	$("#del_confirm").modal('show');
}
function doDel() {
	var orderid = $("#view_data").data("delorderid");
	var index = $("#view_data").data("delindex");
	var obj = {};
	obj.orderid = orderid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/ProductPoint/delProductPointOrder/orderid/' + orderid, function(data, status) {
		if (data == "1") {
			util.successMsg('撤销成功');
			$("#del_confirm").modal('hide');
			ProductAdd.init("",index);
		}
		util.hideLoading();
	},
	objdata,
	function(XMLHttpRequest, textStatus, errorThrown) {
		util.errorMsg('内部服务器错误');
		util.hideLoading();
	});

}
function openProductPointOrderSend(orderid,index) {
	$("#view_data").data("sendorderid", orderid);
	$("#view_data").data("sendindex", index);
	$("#send_confirm").modal('show');
}
function doSend() {
	var orderid = $("#view_data").data("sendorderid");
	var index = $("#view_data").data("sendindex");
	var expressno = $("#expressno").val().trim();
	var expressname = $("#expressname").val().trim();
	var util = new Util();
	if(util.isNullStr(expressno)){
		$('#expressnogroup').removeClass('has-success');
		$('#expressnogroup').addClass('has-error');
		return false;
	}else{
		$('#expressnogroup').removeClass('has-error');
		$('#expressnogroup').addClass('has-success');
	}
	if(util.isNullStr(expressname)){
		$('#expressnamegroup').removeClass('has-success');
		$('#expressnamegroup').addClass('has-error');
		return false;
	}else{
		$('#expressnamegroup').removeClass('has-error');
		$('#expressnamegroup').addClass('has-success');
	}
	$("#btnSend").button("loading");
	var obj = {};
	obj.orderid = orderid;
	obj.expressno = expressno;
	obj.expressname = expressname;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	util.showLoading();
	util.postUrl('/Admin/ProductPoint/SendProductPointOrder/orderid/' + orderid, function(data, status) {
		if (data == "1") {
			util.successMsg('发货成功');
			$("#send_confirm").modal('hide');
			ProductAdd.init("",index);
		}
		util.hideLoading();
		$("#btnSend").button("reset");
	},
	objdata,
	function(XMLHttpRequest, textStatus, errorThrown) {
		util.errorMsg('内部服务器错误');
		util.hideLoading();
		$("#btnSend").button("reset");
	});

}
$(document).ready(function() {
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)){
		start = 0;
	}
	if(util.isNullStr(params)){
		ProductAdd.init("../index.php/Admin/ProductPoint/findProductPointOrder",start,0);
	}else{
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		$('#keyword_search').val(arrval0[1]);
		$('#status_search').val(arrval1[1]);
		ProductAdd.init("../index.php/Admin/ProductPoint/findProductPointOrder",start,0);
	}
	$("#li_finish").bind('click', function() {
		ProductAdd.init("../index.php/Admin/ProductPoint/findProductPointOrder",start,1);
	});
	$("#li_send").bind('click', function() {
		ProductAdd.init("../index.php/Admin/ProductPoint/findProductPointOrder",start,0);
	});
});