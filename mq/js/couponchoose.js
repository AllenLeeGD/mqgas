//mui.init({
//	pullRefresh: {
//		container: '#pullrefresh',
//		down: {
//			contentdown: '',
//			contentover: '',
//			contentrefresh: '正在刷新...',
//			callback: pulldownRefresh
//		},
//		up: {
//			contentdown: '',
//			contentrefresh: '正在载入...',
//			contentnomore: '没有数据了',
//			callback: pullupRefresh
//		}
//	}
//});

var numbers = 0;
function pulldownRefresh() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var userid = util.getvalueincache("USERID");
	
	var params = {};
	params.startindex = 0; //起始记录
	params.pagesize = 10; //每次刷新的记录数
	mui.ajax(edu_host + '/index.php/Mq/Coupon/findMyCoupons/userid/' + userid , {
		type: 'post',
		data: params,
		success: function(data) {
			if(data && data[0]) {
				mui.ajax('couponchoose.txt', {
					success: function(templatetext) {
						try {
							if(data[0] != null && data.length > 0) {
								var result = "";
								for(var i = 0; i < data.length; i++) {
									var item = data[i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{pkid\}", item.pkid);
									this_temp = this_temp.replace("\$\{usevalue\}", item.usevalue);
									this_temp = this_temp.replace("\$\{money\}", item.usevalue);
									this_temp = this_temp.replace("\$\{enddate\}", new Date(item.enddate*1000).Format("yyyy-MM-dd"));									
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "images/juan.png");
									result += this_temp;
								}
								document.getElementById("div_list").innerHTML = result;

							} else {
								document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">暂时没有优惠券</span></li>";
							}
							document.getElementById('startindex').innerHTML = data[1];
						} catch(e) {
							document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">无法加载数据</span></li>";
						} finally {
//							mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
//							mui('#pullrefresh').pullRefresh().refresh(true);
							util.loadingEnd();
						}
					},
					error: function(xhr, type, errorThrown) {
						document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
						util.loadingEnd();
//						mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
//						mui('#pullrefresh').pullRefresh().refresh(true);
					}
				});
			} else {
				util.loadingEnd();
//				mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
//				mui('#pullrefresh').pullRefresh().refresh(true);
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">暂时没有优惠券</span></li>";
			}
		}
	});
}

document.getElementById("done").addEventListener("tap",function(){
	var util = new Util();
	var choose = document.getElementsByName("couponcheckbox");
	var result = new Array();
	var money = 0;
	for(var i = 0;i<choose.length;i++){
		var _item = choose[i];
		if(_item.checked==true){
			result.push(_item.getAttribute("id"));
			money+=parseFloat(_item.getAttribute("money"));
		}
	}
	if(result.length>numbers){
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />您最多只能使用"+numbers+"张优惠券");
	}else{
		var resultobj = {};
		resultobj.money=money;
		resultobj.list = result;
		resultobj.numbers = numbers;
		util.putvalueincache("usecoupon",JSON.stringify(resultobj));
		document.location.href="index.html?usecoupon=yes";
	}
});
(function($, doc) {
	$.ready(function() {
		mui.init();
		mui(".mui-content").scroll();
		var util = new Util();
		numbers = util.getParam("nu");
		document.getElementById("title").innerHTML="使用优惠券(可用"+numbers+"张)"
		pulldownRefresh();
	});
})(mui, document);