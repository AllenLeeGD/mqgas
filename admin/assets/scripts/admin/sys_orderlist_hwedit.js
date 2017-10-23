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
//订单
var orderdetails = {};
function deleteDetail(pkid){
	orderdetails[pkid] = undefined;
	$("#"+pkid).remove();
}
function add15jf(productcount,price){
	var util = new Util();
	var item = {};
	item.pkid = util.uuidFast();
	item.productname = "15KG角阀";
	item.productcount = productcount;
	item.bottleprice = price;
	item.pid = "uuid02";
	item.pname = "15KG";
	item.jid = "uuid11";
	item.jname = "角阀";
	var content = "<tr id=\""+item.pkid+
	"\"><td>15KG角阀</td><td>"+productcount+"</td><td>"+price+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
	orderdetails[item.pkid] = item;
	$("#detailBody").append(content);
}
function add15zf(productcount,price){
	var util = new Util();
	var item = {};
	item.pkid = util.uuidFast();
	item.productname = "15KG直阀";
	item.productcount = productcount;
	item.bottleprice = price;
	item.pid = "uuid02";
	item.pname = "15KG";
	item.jid = "uuid10";
	item.jname = "直阀";
	var content = "<tr id=\""+item.pkid+
	"\"><td>15KG直阀</td><td>"+productcount+"</td><td>"+price+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
	orderdetails[item.pkid] = item;
	$("#detailBody").append(content);
}
function add50qx(productcount,price){
	var util = new Util();
	var item = {};
	item.pkid = util.uuidFast();
	item.productname = "50KG气相";
	item.productcount = productcount;
	item.bottleprice = price;
	item.pid = "uuid03";
	item.pname = "50KG";
	item.rid = "uuid20";
	item.rname = "气相";
	var content = "<tr id=\""+item.pkid+
	"\"><td>50KG气相</td><td>"+productcount+"</td><td>"+price+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
	orderdetails[item.pkid] = item;
	$("#detailBody").append(content);
}
function add50yx(productcount,price){
	var util = new Util();
	var item = {};
	item.pkid = util.uuidFast();
	item.productname = "50KG液相";
	item.productcount = productcount;
	item.bottleprice = price;
	item.pid = "uuid03";
	item.pname = "50KG";
	item.rid = "uuid21";
	item.rname = "液相";
	var content = "<tr id=\""+item.pkid+
	"\"><td>50KG液相</td><td>"+productcount+"</td><td>"+price+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
	orderdetails[item.pkid] = item;
	$("#detailBody").append(content);
}
function showInput(ptype,ptitle){
	$("#modelparam").data('ptype',ptype);
	$("#inputtitle").html("购买"+ptitle);
	$("#productcount").val('');
	$("#doinput").modal('show');
}
function doInput(){
	var ptype = $("#modelparam").data('ptype');
	var productcount = $("#productcount").val();
	var memberid = $("#modelparam").data("pkid");
	if(ptype=="15jf"){
		util.postUrl(
			"/Mq/Mobileorder/getPrice/memberid/"+memberid+"/pid/uuid02/rid/empty/qid/empty/jid/uuid11",
			function(data, status) { //如果调用php成功  
				if(data) {
					add15jf(productcount,data);
					$("#doinput").modal('hide');
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
			}
		);
	}else if(ptype=="15zf"){
		util.postUrl(
			"/Mq/Mobileorder/getPrice/memberid/"+memberid+"/pid/uuid02/rid/empty/qid/empty/jid/uuid10",
			function(data, status) { //如果调用php成功  
				if(data) {
					add15zf(productcount,data);
					$("#doinput").modal('hide');
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
			}
		);
	}else if(ptype=="50qx"){
		util.postUrl(
			"/Mq/Mobileorder/getPrice/memberid/"+memberid+"/pid/uuid03/rid/uuid20/qid/empty/jid/empty",
			function(data, status) { //如果调用php成功  
				if(data) {
					add50qx(productcount,data);
					$("#doinput").modal('hide');
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
			}
		);
	}else if(ptype=="50yx"){
		util.postUrl(
			"/Mq/Mobileorder/getPrice/memberid/"+memberid+"/pid/uuid03/rid/uuid21/qid/empty/jid/empty",
			function(data, status) { //如果调用php成功  
				if(data) {
					add50yx(productcount,data);
					$("#doinput").modal('hide');
				}
			},
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
			}
		);
	}
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
						"name": "mobile_search",
						"value": $("#mobile_search").val()
					}, {
						"name": "address_search",
						"value": $("#address_search").val()
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
function bulidData() {
	var util = new Util();
	if(util.isNullStr(send_obj.membername) || util.isNullStr(send_obj.mobile) || util.isNullStr(send_obj.address) ) {
		return false;
	}
	var detailBody = $("#detailBody").html().trim();
	if(util.isNullStr(detailBody)) {
		return false;
	}
	return true;
}

function saveData(istatus) {
	var util = new Util();
	if(istatus==0){
		btn = "btnSave";
	}else{
		btn = "btnStart";
	}
	$("#"+btn).button('loading');
	var obj = bulidData();
	if(obj == false) {
		$("#btnSave").button("reset");
		util.errorMsg('请填写完整信息');
		return;
	}
	var items = new Array();
	for(var keyname in orderdetails){
		if(orderdetails[keyname] != undefined){
			items.push(orderdetails[keyname]);
		}
	}
	send_obj.itemlist = items;
	var param = {};
	param.content = base64_encode(JSON.stringify(send_obj));
	var orderid = util.getParam("pkid");
	if(obj != false) {
		util.showLoading();
		var url = "/Mq/JMOrder/saveOrder/pkid/"+orderid+"/status/"+istatus;
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#"+btn).button("reset");
					util.hideLoading();
					util.successMsg('操作成功');
					setTimeout(function() {
						
					}, 1000);
				} else {
					$("#"+btn).button("reset");
					util.hideLoading();
					util.errorMsg('操作失败');
				}
			},
			param,
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
				util.hideLoading();
				$("#"+btn).button("reset");
			}
		);
	}
}
var send_obj = {};
var send_vue;

function loadData() {
	var util = new Util();
	var pkid = util.getParam("pkid");
	
	util.postUrl(
		"/Mq/JMOrder/findOrderbyPkid/pkid/"+pkid,
		function(data, status) {
			send_obj.memberid = data.buyer;
			send_obj.membername = data.buyername;
			send_obj.mobile = data.buyermobile;
			send_obj.address = data.buyeraddress;
			send_obj.remark = data.remark;
			send_vue = new Vue({
				el: "#form_app",
				data: {
					sendobj: send_obj
				}
			});
			util.postUrl(
				"/Mq/Member/findMemberPriceinfo/memberid/"+data.buyer,
				function(rdata, rstatus) {
					$("#priceinfo").val(rdata);
				}
			);
			ProviderOrder.init("../index.php/Mq/Order/findOrdersByMemberid/memberid/"+data.buyer, 0);
			$("#modelparam").data("pkid",data.buyer);
			for(var i = 0;i<data.details.length;i++){
				var item = data.details[i];
				item.pkid = util.uuidFast();
				item.productname = item.productname;
				item.productcount = item.productcount;
				item.bottleprice = item.bottleprice;
				if(item.pname=="15KG"){
					item.pid = item.pid;
					item.pname = item.pname;
					item.jid = item.jid;
					item.jname = item.jname;
					var content = "<tr id=\""+item.pkid+
					"\"><td>"+item.productname+"</td><td>"+item.productcount+"</td><td>"+item.bottleprice+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
					
				}else if(item.pname=="50KG"){
					item.pid = item.pid;
					item.pname = item.pname;
					item.rid = item.rid;
					item.rname = item.rname;
					var content = "<tr id=\""+item.pkid+
					"\"><td>"+item.productname+"</td><td>"+item.productcount+"</td><td>"+item.bottleprice+"</td><td>15KG</td><td><a class='btn btn-xs default'  data-toggle='modal' onclick=\"deleteDetail('"+item.pkid+"')\"><i class='fa fa-pencil'></i> &nbsp;删除&nbsp;</a></td></tr>";
					
				}
				orderdetails[item.pkid] = item;
				$("#detailBody").append(content);
			}
		}
	);
}
$("#information_tab").click(function(){
	$("#information_div").show();
	$("#btndiv").show();
	$("#orders_div").hide();
//	$("#details_div").hide();
	$("#detail_btn").show();
});
$("#orders_tab").click(function(){
	$("#information_div").hide();
	$("#btndiv").hide();
	$("#orders_div").show();
//	$("#details_div").hide();
	$("#detail_btn").hide();
});
//$("#detail_tab").click(function(){
//	$("#information_div").hide();
//	$("#btndiv").hide();
//	$("#orders_div").hide();
//	$("#details_div").show();
//	$("#detail_btn").show();
//});

$(document).ready(function() {
	if(jQuery().datepicker) {
		$('.date-picker').datepicker({
			rtl: App.isRTL(),
			autoclose: true
		});
		$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	}
	loadData();
	$("#btnSave").bind('click', function() {
		saveData(0);
	});
	$("#btnStart").bind('click', function() {
		saveData(1);
	});
});