function openOrderConfirm(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#do_confirm").modal('show');
}

function openOrderRefund(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#refundMessage").val("");
	$("#do_refund").modal('show');
}

function openOrderComplete(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#refundMessage").val("");
	$("#complete_order_id").html("订单号:"+pkid);
	$("#do_complete").modal('show');
}

function openOrderCancelRefund(pkid, index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#refuseMessage").val("");
	$("#do_cancelrefund").modal('show');
}

function doConfirm() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var msg = $("#returnMsg").val();
	var objdata = {};
	objdata.content = msg;
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Order/send/bid/' + pkid, function(data, status) {
			$("#returnMsg").val("");
			if(data == "yes") {
				util.successMsg('确认成功');
				$("#do_confirm").modal('hide');
			} else {
				util.errorMsg('确认失败');
			}
			util.getUrl('/Mq/Order/countProductOrder/status/0', function(data, status) {
				$("#waitnum").html(data);
			});
			ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", index);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doComplete() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Order/complete/bid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('操作成功');
				$("#do_complete").modal('hide');
			} else {
				util.errorMsg('操作失败');
			}
			ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/1", index);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doRefund() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var refundMessage = $("#refundMessage").val();
	var obj = {};
	var objdata = {};
	objdata.content = refundMessage;
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/Order/refundProductOrder/pkid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('操作成功');
				$("#do_refund").modal('hide');
			} else {
				util.errorMsg('操作失败');
			}
			util.getUrl('/Mq/Order/countProductOrder/status/0', function(data, status) {
				$("#waitnum").html(data);
			});
			ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", index);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doCancelRefund() {
	var util = new Util();
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = $("#refuseMessage").val();
	if(util.isNullStr(content)) {
		util.errorMsg("请填写拒绝理由");
		return;
	}
	var objdata = {};
	objdata.content = content;

	util.showLoading();
	util.postUrl('/Mq/Order/refuseProductOrder/pkid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('拒绝退款成功');
				$("#do_cancelrefund").modal('hide');
			} else {
				util.errorMsg('拒绝退款失败');
			}
			util.getUrl('/Mq/Order/countProductOrder/status/0', function(data, status) {
				$("#waitnum").html(data);
			});
			ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", index);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function openProductDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-pddetail");
	util.showLoading();
	openmodal.load('sys_pointpddetail.html', '', function() {
		util.getUrl("/Admin/Admin/findProductByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var pdname = objinfo[0].pdname;
				var studypoint = "普通 : " + objinfo[0].generalprice + "<br />尊贵 : " + objinfo[0].vipprice;
				var vipdiscount = objinfo[0].vipdiscount;
				var pdpic = objinfo[0].imgpath;
				if(!util.isNullStr(pdpic)) {
					pdpic = "<img src=\"" + edu_host + "/Upload/" + pdpic + "\" style=\"height:100px;\">";
				} else {
					pdpic = "";
				}
				var remarks = objinfo[0].description;
				var updowntag = objinfo[0].updowntag;
				var sortno = objinfo[0].sortno;
				if(updowntag == 0) {
					updowntag = "下架";
				} else if(updowntag == 1) {
					updowntag = "上架";
				}
				$('#pdname_detail').html(pdname);
				$('#studypoint_detail').html(studypoint);
				$('#pdpic_detail').html(pdpic);
				$('#description_detail').html(remarks);
				$('#updowntag_detail').html(updowntag);
				$('#vipdiscount_detail').html(vipdiscount);
				$('#sortno_detail').html(sortno);
			} catch(err) {
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

function openOrderDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-modal");
	util.showLoading();
	openmodal.load('sys_orderdetail.html', '', function() {
		util.postUrl('/Mq/Order/findProductOrderByPkid/pkid/' + pkid, function(data, status) {
			if(data != "no") {
				try {
					var objdata = JSON.parse(data);
					$('#div_orderinfo').html("订单编号:&nbsp;&nbsp;&nbsp;" + objdata.pkid + "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" + objdata.buytime + "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" + objdata.status + "");
					$('#l_aname').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>收&nbsp;货&nbsp;人:</strong>&nbsp;&nbsp;" + objdata.buyername + "");
//					$('#sendtime').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>预&nbsp;约&nbsp;时&nbsp;间:</strong>&nbsp;&nbsp;" + objdata.sendtime + "");
					$('#l_tel').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>电&nbsp;　&nbsp;话:</strong>&nbsp;&nbsp;" + objdata.buyermobile + "");
					$('#l_address').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>地&nbsp;　&nbsp;址:</strong>&nbsp;&nbsp;" + objdata.buyeraddress + "");
					$('#l_remark').html("<strong style='width:80px;text-align:right; display:inline-block;height:35px'>留&nbsp;　&nbsp;言:</strong>&nbsp;&nbsp;" + objdata.remark + "");
					var paytype = objdata.paytype;
					var line1 = "";
					var line2 = "";
					var line3 = "";
					var line4 = "";
					if(paytype == 0) {
						$('#l_paytype').html("<strong>支付方式:</strong>&nbsp;&nbsp;微信支付");
					} else {
						$('#l_paytype').html("<strong>支付方式:</strong>&nbsp;&nbsp;现金支付");
					}
					if(objdata.status == "拒绝退款") {
						$('#l_remark').html("<strong>拒绝理由:</strong>&nbsp;&nbsp;" + objdata.refuseremark);
						$('#l_paytime').html("<strong>拒绝时间:</strong>&nbsp;&nbsp;" + (new Date(objdata.refusetime * 1000)).Format("yyyy-MM-dd hh:mm:ss"));
					} else if(objdata.status == "完成退款") {
						$('#l_remark').html("<strong>留言:</strong>&nbsp;&nbsp;" + objdata.refundremark);
						$('#l_paytime').html("<strong>退款时间:</strong>&nbsp;&nbsp;" + objdata.refundtime);
					} else if(objdata.status == "申请退款") {
						$('#l_remark').html("<strong>留言:</strong>&nbsp;&nbsp;" + objdata.remark);
						$('#l_paytime').html("<strong>申请时间:</strong>&nbsp;&nbsp;" + objdata.prerefundtime);
					}
					var itemlist = objdata.itemlist;
					var totalmoney = 0;
					//						var item = itemlist[a];
					var template = "<tr><td><div class=\"product-img-label\" >" +
						"<span>${pdname}</span>" +
						"</div></td><td>${qty}</td>\<td>¥ ${price}</td><td>¥ ${subtotal}</td></tr>";
					//var index = parseInt(a,10)+1;
					var pdname = "15KG液化气";
					var qty = objdata.buycount;
					var price = objdata.price;
					var subtotal = (parseInt(objdata.buycount, 10) * parseFloat(objdata.price)).toFixed(2);
					totalmoney = (parseFloat(totalmoney) + parseFloat(subtotal)-parseFloat(objdata.coupon)).toFixed(2);
					template = template.replace('\$\{pdname\}', pdname);
					template = template.replace('\$\{qty\}', qty);
					template = template.replace('\$\{price\}', price);
					template = template.replace('\$\{subtotal\}', subtotal);
					var old = $("#tpl_itemlist").html();
					$("#tpl_itemlist").html(old + template);
					$('#coupon').html("¥ " + objdata.coupon);
					$('#totalmoney').html("¥ " + totalmoney);
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
						"name": "keyword_search",
						"value": $("#keyword_search").val()
					}, {
						"name": "buyername_search",
						"value": $("#buyername_search").val()
					}, {
						"name": "status_search",
						"value": $("#status_search").val()
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
var oldcount=0;
function loadNewMessage() {
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)) {
		start = 0;
	}
	util.getUrl('/Mq/Order/countProductOrder/status/0', function(data, status) {
		$("#waitnum").html(data);
		if(data>oldcount){
			document.title="您有新的订单需要处理";
		}
		oldcount = data;
	});
	
	if($("#success_tab").hasClass("active")){
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/1", start);
	}else if($("#wait_tab").hasClass("active")){
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", start);	
	}else if($("#complete_tab").hasClass("active")){
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/2", start);	
	}
	
	setTimeout(function() {
		loadNewMessage();
	}, 60000);
}
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
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", start);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		$('#keyword_search').val(arrval0[1]);
		$('#buyername_search').val(arrval1[1]);
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", start);
	}
	//获得进行中的订单数
	util.getUrl('/Mq/Order/countProductOrder/status/0', function(data, status) {
		oldcount = data;
		$("#waitnum").html(data);
	});
	//进行中的订单
	$("#wait_tab").bind('click', function() {
		readed = true;
		document.title="订单管理";
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0", 0);
	});
	//派送中的订单
	$("#success_tab").bind('click', function() {
		readed = true;
		document.title="订单管理";
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/1", 0);
	});
	//已完成的订单
	$("#complete_tab").bind('click', function() {
		readed = true;
		document.title="订单管理";
		ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/2", 0);
	});
	loadNewMessage();
});
//确认订单
function doSendConfirm() {
	var bid = $("#agent_order_data").data("send_bid");
	$("#confirm_send_btn").button("loading");
	var util = new Util();
	util.getUrl("/Mq/Order/send/bid/" + bid, function(data, status) {
		if(data == "yes") {
			util.successMsg('订单确认成功');
			$("#ajax-send").modal('hide');
			ProviderOrder.init("../index.php/Mq/Order/findProductOrderByStatus/status/0");
			//			util.getUrl('/Provider/ProductOnSale/getProviderOrderCount/status/1', function(data, status) {
			//				$("#send").html(data);
			//			});
			//			util.getUrl('/Provider/ProductOnSale/getProviderOrderCount/status/2', function(data, status) {
			//				$("#receive").html(data);
			//			});
			$("#confirm_send_btn").button("reset");
		}
	}, function() {
		$("#confirm_send_btn").button("reset");
	});
}

function receive_order_btn(bid) {
	$("#agent_order_data").data("receive_bid", bid);
	$("#receive_confirm").modal('show');
}

function comment_order_btn(bid) {
	$("#agent_order_data").data("comment_bid", bid);
	$("#comment_confirm").modal('show');
}