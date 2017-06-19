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


function pulldownRefresh() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var userid = util.getvalueincache("USERID");	
	var params = {};
	params.startindex = 0; //起始记录
	params.pagesize = 10; //每次刷新的记录数
	mui.ajax(edu_host + '/index.php/Mq/Coupon/getMyCoupons/userid/' + userid , {
		type: 'post',
		data: params,
		success: function(data) {
			if(data != "has" && data[0]) {
				document.getElementById("mytitle").innerHTML="领用优惠券成功";
				mui.ajax('coupon.txt', {
					success: function(templatetext) {
						try {
							if(data[0] != null && data.length > 0) {
								var result = "";
								for(var i = 0; i < data.length; i++) {
									var item = data[i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{usevalue\}", item.usevalue);
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
				document.getElementById("mytitle").innerHTML="领用优惠券失败";
				util.loadingEnd();
//				mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
//				mui('#pullrefresh').pullRefresh().refresh(true);
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">优惠券已经领完，或者您已经领取过优惠券，不能重复领取</span></li>";
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
	var url = '/index.php/Mq/Coupon/getMyCoupons/userid/' + userid ;
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
			if(data!= "has" && data[0]) {
				document.getElementById("mytitle").innerHTML="领用优惠券成功";
				mui.ajax('coupon.txt', {
					success: function(templatetext) {
						try {
							if(data != null && data.length > 0) {
								var result = "";
								for(var i = 0; i < data.length; i++) {
									var item = data[i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{usevalue\}", item.usevalue);
									this_temp = this_temp.replace("\$\{enddate\}", new Date(item.enddate*1000).Format("yyyy-MM-dd"));									
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "images/juan.png");									
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
							util.loadingEnd();
						}
					},
					error: function(xhr, type, errorThrown) {
							mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
							util.loadingEnd();
							var sOld = document.getElementById('div_list').innerHTML;
							document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错</span></li>";
						} //end error
				}); //end load txt
			} else {
				document.getElementById("mytitle").innerHTML="领用优惠券失败";
				mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //可以继续下拉加载
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">您已经领取过优惠券，不能重复领取</span></li>";
			}
		}
	});
}

(function($, doc) {
	$.ready(function() {
		mui.init();
		mui(".mui-content").scroll();
		pulldownRefresh();
	});
})(mui, document);