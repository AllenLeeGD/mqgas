
function openIn(pkid) {
	$("#view_data").data("pkid", pkid);
	$("#incarnumber").val("");
	$("#cun").val("");
	$("#huiempty").val("");
	$("#huifull").val("");
	$("#huishou").val("");
	$("#weight").val("");
	$("#do_in").modal('show');
}

function getouttypestr(type){
	if(type=="1"){
		return "代理商";
	}else if(type=="2"){
		return "门店";
	}else if(type=="3"){
		return "小工商";
	}
}

function doIn() {
	var pkid = $("#view_data").data("pkid");
	var objdata = {};
	objdata.incarnumber = base64_encode($("#incarnumber").val());
	objdata.cun = base64_encode($("#cun").val());
	objdata.huiempty = base64_encode($("#huiempty").val());
	objdata.huifull = base64_encode($("#huifull").val());
	objdata.huishou = base64_encode($("#huishou").val());
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Hsporder/doin/bid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('入库成功');
				$("#do_in").modal('hide');
			} else {
				util.errorMsg('入库失败');
			}
			ProviderOrder.init("../index.php/Mq/Hsporder/findProductOrderByStatus/status/0", 0);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});
}

function openOrderDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-modal");
	util.showLoading();
	openmodal.load('sys_orderdetail_hsp.html', '', function() {
		util.postUrl('/Mq/Hsporder/findProductOrderByPkid/pkid/' + pkid, function(data, status) {
			if(data != "no") {
				try {
					var objdata = JSON.parse(data);
					$('#div_orderinfo').html("订单编号:&nbsp;&nbsp;&nbsp;" + objdata.pkid + "&nbsp;&nbsp;&nbsp;下单时间:&nbsp;&nbsp;&nbsp;" + objdata.buytime+ "&nbsp;&nbsp;&nbsp;下单人:&nbsp;&nbsp;&nbsp;" + objdata.username);
					$('#l_aname').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>客&nbsp;　&nbsp;户:</strong>&nbsp;&nbsp;" + objdata.buyername + "");
//					$('#sendtime').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>预&nbsp;约&nbsp;时&nbsp;间:</strong>&nbsp;&nbsp;" + objdata.sendtime + "");
					$('#l_tel').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>电&nbsp;　&nbsp;话:</strong>&nbsp;&nbsp;" + objdata.buyermobile + "");
					$('#l_address').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>地&nbsp;　&nbsp;址:</strong>&nbsp;&nbsp;" + objdata.buyeraddress + "");
					$('#l_remark').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>备&nbsp;　&nbsp;注:</strong>&nbsp;&nbsp;" + objdata.remark + "");
					
					$('#l_recarnumber').html("<strong>预派车牌号码:</strong>&nbsp;&nbsp;"+objdata.recarnumber);
					$('#l_recardate').html("<strong>预计到达时间:</strong>&nbsp;&nbsp;"+objdata.recardate);
					$('#l_recaroptname').html("<strong>操作人:</strong>&nbsp;&nbsp;"+objdata.recaroptname);
					$('#l_carnumber').html("<strong>送货车牌号码:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.carnumber)?"":objdata.carnumber));
										
					$('#l_incarnumber').html("<strong>入库车牌号码:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.incarnumber)?"":objdata.incarnumber));
					$('#l_inoptname').html("<strong>入库操作人:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.inoptname)?"":objdata.inoptname));
					$('#l_inoptdate').html("<strong>入库时间:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.inoptdate)?"":(new Date(objdata.inoptdate*1000).Format("yyyy-MM-dd hh:mm:ss"))));
					$('#l_cun').html("<strong>客户存瓶:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.cun)?"":objdata.cun));
					$('#l_huiempty').html("<strong>回流瓶空瓶:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.huiempty)?"":objdata.huiempty));
					$('#l_huifull').html("<strong>回流瓶重瓶:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.huifull)?"":objdata.huifull));
					$('#l_huishou').html("<strong>回收瓶:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.huishou)?"":objdata.huishou));
					
					
					var itemlist = objdata.itemlist;
					var result = "";
					for(var i = 0;i<itemlist.length;i++){
						var objdata = itemlist[i];
						var template = "<tr><td><div class=\"product-img-label\" >" +
							"<span>${pdname}</span>" +
							"</div></td><td>${pname}</td>\<td>${productcount}</td></tr>";
						//var index = parseInt(a,10)+1;
						var productname = objdata.productname;
						var pname = objdata.pname;
						var productcount = ((objdata.productcount==0)?"":objdata.productcount);
						var productweight = ((objdata.productweight==0)?"":objdata.productweight);
						
						template = template.replace('\$\{pdname\}', productname);
						template = template.replace('\$\{pname\}', pname);
						template = template.replace('\$\{productcount\}', productcount);
						result+=template
					}
					$("#tpl_itemlist").html(result);
				} catch(err) {
					util.errorMsg(err.message);
				} finally {
					util.hideLoading();
					openmodal.modal('show');
				}

			} else {
				util.hideLoading();
				openmodal.modal('hide');
			}
		});
	});
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
						"name": "order_search",
						"value": $("#order_search").val()
					}, {
						"name": "buyername_search",
						"value": $("#buyername_search").val()
					}, {
						"name": "optname_search",
						"value": $("#optname_search").val()
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

var oldcount=0;
$(document).ready(function() {
	
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)) {
		start = 0;
	}
	if(util.isNullStr(params)) {
		ProviderOrder.init("../index.php/Mq/Hsporder/findProductOrderByStatus/status/0", start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		$('#order_search').val(arrval0[1]);
		$('#buyername_search').val(arrval1[1]);
		$('#optname_search').val(arrval2[1]);
		ProviderOrder.init("../index.php/Mq/Hsporder/findProductOrderByStatus/status/0", start);
	}
	//待入库的订单
	$("#ru_tab").bind('click', function() {
		document.title="票房订单管理";
		ProviderOrder.init("../index.php/Mq/Hsporder/findProductOrderByStatus/status/0", 0);
	});
	//已完成的订单
	$("#complete_tab").bind('click', function() {
		document.title="票房订单管理";
		ProviderOrder.init("../index.php/Mq/Hsporder/findProductOrderByStatus/status/1", 0);
	});
	
});
