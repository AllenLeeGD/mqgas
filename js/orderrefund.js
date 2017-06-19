document.getElementById("submit_info").addEventListener("tap", function() {
	var remarks = document.getElementById("remarks").value;
	var util = new Util();
	if(util.isNullStr(remarks)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写申请理由");
		return false;
	} else {
		document.getElementById("submit_info").disabled=true;
		var obj = {};
		obj.remark = remarks;
		obj.orderid = util.getParam("orderid");
		mui.ajax(edu_host + '/index.php/Mq/Order/refundOrder', {
			type: 'post',
			data:obj,
			success: function(data) {
				if(data == "yes") {
					mui.toast("申请退款成功");
					setTimeout(function(){
						document.location.href = "order.html?ordertype=finished";
					},1000);
				} else {
					mui.toast("申请退款失败");
				}
			}
		});
	}
});
(function($, doc) {
	$.ready(function() {
		mui.init();
		mui(".mui-scroll-wrapper").scroll();
	});
})(mui, document);