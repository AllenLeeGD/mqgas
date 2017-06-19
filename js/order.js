mui.init({
	pullRefresh: {
		container: '#pullrefresh',
		down: {
			contentdown: '',
			contentover: '',
			contentrefresh: '正在刷新...',
			callback: pulldownRefresh
		},
		up: {
			contentdown: '',
			contentrefresh: '正在载入...',
			contentnomore: '没有数据了',
			callback: pullupRefresh
		}
	}
});
mui(".mui-content").scroll();

var btnRefund = '<span orderid="${orderid}" class="mui-btn btn-withdraw" style="font-size:11px;border:1px solid #bbb;padding:3px 10px 2px 10px;color:#bbb;">申请退款</span>';
var btnPay = '<span orderid="${orderid}" money="${money}" class="mui-btn btn-pay" style="font-size:11px;border:1px solid #bbb;padding:3px 10px 2px 10px;color:green;">微信支付</span>';
var btnCancle = '<span orderid="${orderid}" money="${money}" class="mui-btn btn-cancle" style="font-size:11px;border:1px solid #bbb;padding:3px 10px 2px 10px;color:red;">取消订单</span>';

function getStatusstr(status) {
	if(status == 0) {
		return "配送中,请您耐心等待";
	} else if(status == 1) {
		return "配送中,请您耐心等待";
	} else if(status == 2) {
		return "申请退款中,请您耐心等待";
	} else if(status == 3) {
		return "退款被拒绝";
	} else if(status == 4) {
		return "已退款,感谢您的支持";
	} else if(status == 5) {
		return "派送员已经出发,请您耐心等待";
	} else if(status == 6) {
		return "待付款,请使用微信支付";
	} else if(status == 7) {
		return "付款成功,配送中,请您耐心等待";
	} else if(status == 8) {
		return "已完成";
	}
}

function pulldownRefresh() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var userid = util.getvalueincache("USERID");
	//var userid = 'userid-01';
	var status = util.getvalueincache("ORDER_TYPE");
	if(util.isNullStr(status)) {
		util.putvalueincache("ORDER_TYPE", "going");
	} else {
		util.putvalueincache("ORDER_TYPE", status);
	}
	var params = {};
	params.startindex = 0; //起始记录
	params.pagesize = 10; //每次刷新的记录数
	mui.ajax(edu_host + '/index.php/Mq/Order/findOrderByStatus/userid/' + userid + "/status/" + status, {
		type: 'post',
		data: params,
		success: function(data) {
			if(data && data[0]) {
				mui.ajax('orders.txt', {
					success: function(templatetext) {
						try {
							if(data[0] != null && data[0].length > 0) {
								var result = "";
								for(var i = 0; i < data[0].length; i++) {
									var item = data[0][i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{pid\}", item.pkid);
									this_temp = this_temp.replace("\$\{price\}", item.price);
									this_temp = this_temp.replace("\$\{orderid\}", item.pkid);
									this_temp = this_temp.replace("\$\{buycount\}", item.buycount);
									this_temp = this_temp.replace("\$\{coupon\}", util.isNullStr(item.coupon)?"0":item.coupon);
									this_temp = this_temp.replace("\$\{totalmoney\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
									this_temp = this_temp.replace("\$\{statusstr\}", getStatusstr(item.status));
									this_temp = this_temp.replace("\$\{buytime\}", new Date(item.buytime * 1000).Format("yyyy-MM-dd hh:mm:ss"));
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/images/yun.png");
									this_temp = this_temp.replace("\$\{paytype\}", (item.paytype==0?"微信支付":"现金支付"));
									if(item.status == 5 && item.paytype=="0") {
										btnRefund = btnRefund.replace("\$\{orderid\}", item.pkid);
										this_temp = this_temp.replace("\$\{btns\}", btnRefund);
									} else if(item.status == 6  || item.status==0 ) {
										var btnPaycopy = btnPay;
										btnPaycopy = btnPaycopy.replace("\$\{orderid\}", item.pkid);
										btnPaycopy = btnPaycopy.replace("\$\{money\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
										var btnCanclecopy = btnCancle;
										btnCanclecopy = btnCanclecopy.replace("\$\{orderid\}", item.pkid);
										btnCanclecopy = btnCanclecopy.replace("\$\{money\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
										if(item.paytype==0){
											var btnresult = btnPaycopy+"&nbsp;&nbsp;"+btnCanclecopy;
										}else{
											var btnresult = btnCanclecopy;
										}
										this_temp = this_temp.replace("\$\{btns\}", btnresult);
									} else {
										this_temp = this_temp.replace("\$\{btns\}", "");
									}
									result += this_temp;
								}
								document.getElementById("div_list").innerHTML = result;

							} else {
								document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
							}
							document.getElementById('startindex').innerHTML = data[1];
						} catch(e) {
							document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">无法加载数据</span></li>";
						} finally {
							mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
							mui('#pullrefresh').pullRefresh().refresh(true);
							util.loadingEnd();
						}
					},
					error: function(xhr, type, errorThrown) {
						document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
						util.loadingEnd();
						mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
						mui('#pullrefresh').pullRefresh().refresh(true);
					}
				});
			} else {
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}
/**
 * 上拉加载具体业务实现
 */
function pullupRefresh() {
	var util = new Util();
	var userid = util.getvalueincache("USERID");
	//var userid = 'userid-01';
	var status = util.getvalueincache("ORDER_TYPE");
	if(util.isNullStr(status)) {
		util.putvalueincache("ORDER_TYPE", "going");
	} else {
		util.putvalueincache("ORDER_TYPE", status);
	}
	var url = '/index.php/Mq/Order/findOrderByStatus/userid/' + userid + '/status/' + status;
	var params = {};
	params.startindex = document.getElementById('startindex').innerHTML; //起始记录
	params.pagesize = 10; //每次刷新的记录数
	/*pullup*/
	if(params.startindex == '-1') {
		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
		return;
	}
	mui.ajax(edu_host + url, {
		type: 'post',
		data: params,
		timeout: 10000,
		success: function(data) {
			if(data && data[0]) {
				mui.ajax('orders.txt', {
					success: function(templatetext) {
						try {
							//获得普通帖子数据
							if(data[0] != null && data[0].length > 0) {
								var icount = data[0].length;
								var sOld = document.getElementById('div_list').innerHTML;
								/*pullup*/
								var iIndex = sOld.lastIndexOf('</li>');
								/*pullup*/
								sOld = sOld.substr(0, iIndex + 5);
								var result = "";
								for(var i = 0; i < data[0].length; i++) {
									var item = data[0][i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{pid\}", item.pkid);
									this_temp = this_temp.replace("\$\{price\}", item.price);
									this_temp = this_temp.replace("\$\{orderid\}", item.pkid);
									this_temp = this_temp.replace("\$\{buycount\}", item.buycount);
									this_temp = this_temp.replace("\$\{coupon\}", util.isNullStr(item.coupon)?"0":item.coupon);
									this_temp = this_temp.replace("\$\{totalmoney\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
									this_temp = this_temp.replace("\$\{statusstr\}", getStatusstr(item.status));
									this_temp = this_temp.replace("\$\{buytime\}", new Date(item.buytime * 1000).Format("yyyy-MM-dd hh:mm:ss"));
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/images/yun.png");
									if(item.status == 5 && item.paytype==0) {
										btnRefund = btnRefund.replace("\$\{orderid\}", item.pkid);
										this_temp = this_temp.replace("\$\{btns\}", btnRefund);
									} else if(item.status == 6  || item.status==0 ) {
										var btnPaycopy = btnPay;
										btnPaycopy = btnPaycopy.replace("\$\{orderid\}", item.pkid);
										btnPaycopy = btnPaycopy.replace("\$\{money\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
										var btnCanclecopy = btnCancle;
										btnCanclecopy = btnCancle.replace("\$\{orderid\}", item.pkid);
										btnCanclecopy = btnCancle.replace("\$\{money\}", (item.price*item.buycount-(util.isNullStr(item.coupon)?0:item.coupon)));
										if(item.paytype==0){
											var btnresult = btnPaycopy+"&nbsp;&nbsp;"+btnCanclecopy;
										}else{
											var btnresult = btnCanclecopy;
										}
										this_temp = this_temp.replace("\$\{btns\}", btnresult);
									} else {
										this_temp = this_temp.replace("\$\{btns\}", "");
									}
									result += this_temp;
								}
								document.getElementById('div_list').innerHTML = sOld + result;

							} else {
								mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //不允许继续下拉
							}
							document.getElementById('startindex').innerHTML = data[1]; //起始记录数
						} catch(err) {
							mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
							var sOld = document.getElementById('div_list').innerHTML;
							document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
						} finally {
							if(data[1] == -1) { //已经到最后的记录
								mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
								//var sOld = document.getElementById('div_list').innerHTML;
								//document.getElementById('div_list').innerHTML = sOld + "<div class=\"mt-10\" style=\"text-align:center;\"><span class=\"font-size-14 ft-grey\">没有数据了</span></div>";
								/*pullup*/ //document.getElementById("a_getmore").innerHTML = "没有数据了";
								/*pullup*/ //document.getElementById("a_getmore").addEventListener("tap", function(e) {
								//});
							} else {
								mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //可以继续下拉加载
								/*pullup*/ //var sOld = document.getElementById('div_list').innerHTML;
								/*pullup*/ //document.getElementById('div_list').innerHTML = sOld + "<div id=\"a_getmore\" style=\"text-align:center;padding-top:10px;padding-bottom:20px;\" class=\"ft-grey font-size-14\">显示更多</div>";
								/*pullup*/
								//if(document.getElementById("a_getmore")){
								//document.getElementById("a_getmore").disabled = false;
								/*pullup*/
								//document.getElementById("a_getmore").addEventListener("tap", function(e) {
								//document.getElementById("a_getmore").disabled = true;
								//document.getElementById("a_getmore").innerHTML = '加载中...';
								//pullupRefresh();
								//});
								//}
							}

						}
					},
					error: function(xhr, type, errorThrown) {
							mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
							var sOld = document.getElementById('div_list').innerHTML;
							document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错</span></li>";
						} //end error
				}); //end load txt
			} else {
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}
document.getElementById("statusGoing").addEventListener("tap", function() {
	document.location.href = "order.html?ordertype=going";
});
document.getElementById("statusFinished").addEventListener("tap", function() {
	document.location.href = "order.html?ordertype=finished";
});

(function($, doc) {
	$.ready(function() {
		mui.init();
		var util = new Util();
		document.getElementById("aHome").addEventListener("tap", function(e) {
			document.location.href = "index.html";
		});
		var ordertype = util.getParam("ordertype");
		if(ordertype == "finished") {
			util.putvalueincache("ORDER_TYPE", "finished");
			pulldownRefresh();
			document.getElementById("statusGoing").classList.remove("mui-active");
			document.getElementById("statusFinished").classList.add("mui-active");
		} else {
			util.putvalueincache("ORDER_TYPE", "going");
			pulldownRefresh();
			document.getElementById("statusFinished").classList.remove("mui-active");
			document.getElementById("statusGoing").classList.add("mui-active");
		}
		var btnArray = ['确定', '取消'];

		$('#div_list').on('tap', '.btn-withdraw', function(event) {
			var elem = this;
			var li = elem.parentNode.parentNode;
			var orderid = elem.getAttribute('orderid');
			mui.confirm('确定申请退款吗？', '退款', btnArray, function(e) {
				if(e.index == 0) {
					document.location.href = "orderrefund.html?orderid=" + orderid;

				} else {
					setTimeout(function() {
						$.swipeoutClose(li);
					}, 0);
				}
			});
		});
		
		$('#div_list').on('tap', '.btn-cancle', function(event) {
			var elem = this;
			var li = elem.parentNode.parentNode;
			var orderid = elem.getAttribute('orderid');
			mui.confirm('确定取消订单吗？', '取消', btnArray, function(e) {
				if(e.index == 0) {
					mui.ajax(edu_host + '/index.php/Mq/Order/cancleOrder/orderid/' +orderid, {
						type: 'post',
						success: function(data) {
							if(data=="yes"){
								mui.toast("取消成功");
								pulldownRefresh();
							}else{
								mui.toast("取消失败");
							}
						}	
					});
					
				} else {
					setTimeout(function() {
						$.swipeoutClose(li);
					}, 0);
				}
			});
		});

		$('#div_list').on('tap', '.btn-pay', function(event) {
			var util = new Util();
			var params = {};
			var elem = this;
			var li = elem.parentNode.parentNode;
			//			var util = new Util();
			params.orderid = elem.getAttribute('orderid');
			params.openid = util.getvalueincache("OPENID");
			params.money = elem.getAttribute('money');

//			var temp = document.getElementById("dynamicForm");
//			temp.setAttribute("method", 'post');
//			temp.setAttribute("action", "/Payment/WX/gotopay.php");
//			temp.innerHTML = "";
//			var opt = document.createElement("input");
//			opt.setAttribute("type", "hidden");
//			opt.setAttribute("name", "payorderid");
//			opt.setAttribute("value", params.orderid);
//			temp.appendChild(opt);
//			var opt2 = document.createElement("input");
//			opt2.setAttribute("type", "hidden");
//			opt2.setAttribute("name", "paymoney");
//			opt2.setAttribute("value", params.money);
//			temp.appendChild(opt2);
//			var opt3 = document.createElement("input");
//			opt3.setAttribute("type", "hidden");
//			opt3.setAttribute("name", "payopenid");
//			opt3.setAttribute("value", params.openid);
//			temp.appendChild(opt3);
//
//			temp.submit();
			document.location.href="/Payment/WX/gotopay.php?payorderid="+params.orderid+"&paymoney="+params.money+"&payopenid="+params.openid;
		});
	});
})(mui, document);