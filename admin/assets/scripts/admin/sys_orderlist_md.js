function openCun(pkid) {
	$("#view_data").data("pkid", pkid);
	$("#cunmsg").val('');
	$("#do_cun").modal('show');
}
function openShou(pkid) {
	$("#view_data").data("pkid", pkid);
	$("#do_shou").modal('show');
}
function loadCars(did){
	var util = new Util();	
	var url = "/Mq/JMOrder/loadCars/did/"+did;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue .$data.carids = data;
			$("#carid").val("");
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
}
var send_vue ;
function openPei(pkid,did) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("did", did);
	var util = new Util();	
	var url = "/Mq/JMOrder/loadSongqis/did/"+did;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:{},songqiids:data,carids:[]}
			});
			$("#songqiid").val("");
			loadCars(did);
			$("#do_fen").modal('show');
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}


function doShou() {
	var pkid = $("#view_data").data("pkid");
	var objdata = {};
	var util = new Util();
	util.showLoading();
	util.postUrl('/Mq/JMOrder/shou/bid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('确认收款成功');
				$("#do_shou").modal('hide');
			} else {
				util.errorMsg('确认收款失败');
			}
			ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/4", 0);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doCun() {
	var pkid = $("#view_data").data("pkid");
	var objdata = {};
	objdata.cunmsg = $("#cunmsg").val();
	var util = new Util();
	if(util.isNullStr(objdata.cunmsg)){
		util.errorMsg('请填写存款信息');
		return;
	}
	util.showLoading();
	util.postUrl('/Mq/JMOrder/cun/bid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('存款成功');
				$("#do_cun").modal('hide');
			} else {
				util.errorMsg('存款失败');
			}
			ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/5", 0);
			util.hideLoading();
		},
		objdata,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
		});

}

function doPei() {
	var util = new Util();
	var pkid = $("#view_data").data("pkid");
	var objdata = {};
	objdata.songqiid = $("#songqiid").val();
	objdata.carid = $("#carid").val();
	objdata.songqiname = $("#songqiid").find("option:selected").text();
	objdata.carnumber = $("#carid").find("option:selected").text();
	if(util.isNullStr(objdata.songqiid) && util.isNullStr(objdata.carid)){
		util.errorMsg('请选择送气工或车辆');
		return;
	}
	util.showLoading();
	util.postUrl('/Mq/JMOrder/dopei/bid/' + pkid, function(data, status) {
			if(data == "yes") {
				util.successMsg('分配成功');
				$("#do_fen").modal('hide');
			} else {
				util.errorMsg('分派失败');
			}
			ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/3", 0);
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
	openmodal.load('sys_orderdetail_jm.html', '', function() {
		util.postUrl('/Mq/JMOrder/findProductOrderByPkid/pkid/' + pkid, function(data, status) {
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
					$('#l_mname').html("<strong>门店:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.mname)?"":objdata.mname));
					$('#l_pname').html("<strong>片区:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.pname)?"":objdata.pname));
					$('#l_songqiname').html("<strong>送气工:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.songqiname)?"":objdata.songqiname));
					$('#l_carnumber').html("<strong>送气车辆:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.carnumber)?"":objdata.carnumber));
					$('#l_shouoptname').html("<strong>收款操作人:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.shouoptname)?"":objdata.shouoptname));
					$('#l_cuntime').html("<strong>存款时间:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.cuntime)?"":(new Date(objdata.cuntime*1000).Format("yyyy-MM-dd hh:mm:ss"))));
					$('#l_shoutime').html("<strong>收款时间:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.shoutime)?"":(new Date(objdata.shoutime*1000).Format("yyyy-MM-dd hh:mm:ss"))));
					$('#l_cunoptname').html("<strong>存款操作人:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.cunoptname)?"":objdata.cunoptname));
					$('#l_cunmsg').html("<strong>存款信息:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.cunmsg)?"":objdata.cunmsg));
					$('#l_hetime').html("<strong>核款时间:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.hetime)?"":(new Date(objdata.hetime*1000).Format("yyyy-MM-dd hh:mm:ss"))));
					$('#l_heoptname').html("<strong>核款操作人:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.heoptname)?"":objdata.heoptname));
					$('#l_hemsg').html("<strong>核款信息:</strong>&nbsp;&nbsp;"+(util.isNullStr(objdata.hemsg)?"":objdata.hemsg));
					
					
					
					var itemlist = objdata.itemlist;
					if(util.isNullStr(itemlist) || itemlist.length==0){//微信订单,没有orderdetail
						if(paytype == 0) {
							$('#l_paytype').html("<strong>支付方式:</strong>&nbsp;&nbsp;微信支付");
						} else {
							$('#l_paytype').html("<strong>支付方式:</strong>&nbsp;&nbsp;现金支付");
						}
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
					}else{
						$('#l_paytype').html("<strong>支付方式:</strong>&nbsp;&nbsp;无");
						var totalmoney = 0;
						var result = "";
						for(var j = 0;j<itemlist.length;j++){
							var item = itemlist[j];
							var template = "<tr><td><div class=\"product-img-label\" >" +
								"<span>${pdname}</span>" +
								"</div></td><td>${qty}</td>\<td>¥ ${price}</td><td>¥ ${subtotal}</td></tr>";
							template = template.replace('\$\{pdname\}', item.productname);
							template = template.replace('\$\{qty\}', item.productcount);
							template = template.replace('\$\{price\}', item.bottleprice);
							var subtotal = (parseInt(item.productcount, 10) * parseFloat(item.bottleprice)).toFixed(2);
							template = template.replace('\$\{subtotal\}', subtotal);
							result += template;
							totalmoney+=parseFloat(subtotal);
						}
						
						$("#tpl_itemlist").html(result);
						$('#coupon').html("¥ 0");
						$('#totalmoney').html("¥ " + totalmoney);
					}
					
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
						"name": "mobile_search",
						"value": $("#mobile_search").val()
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
		ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/3", 0);
	} else {
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		$('#order_search').val(arrval0[1]);
		$('#buyername_search').val(arrval1[1]);
		$('#mobile_search').val(arrval2[1]);
		ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/3", start);
	}
	
	$("#fen_tab").bind('click', function() {
		document.title="居民小工商订单";
		ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/3", 0);
	});
	
	$("#shou_tab").bind('click', function() {
		document.title="居民小工商订单";
		ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/4", 0);
	});
	
	$("#cun_tab").bind('click', function() {
		document.title="居民小工商订单";
		ProviderOrder.init("../index.php/Mq/JMOrder/findProductOrderByStatus/status/5", 0);
	});
	
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