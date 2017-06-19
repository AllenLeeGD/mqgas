function jsApiCall(params) {
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		params,
		function(res) {
			WeixinJSBridge.log(res.err_msg);
			alert(res.err_code + res.err_desc + res.err_msg);
		}
	);
}

function callpay(params) {
	if (typeof WeixinJSBridge == "undefined") {
		if (document.addEventListener) {
			document.addEventListener('WeixinJSBridgeReady', jsApiCall(params), false);
		} else if (document.attachEvent) {
			document.attachEvent('WeixinJSBridgeReady', jsApiCall(params));
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall(params));
		}
	} else {
		jsApiCall(params);
	}
}
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	document.getElementById("aTel").addEventListener("tap", function(e) {
		document.location.href = "tel:13169666888";
	});
	document.getElementById("aBack").addEventListener("tap", function(e) {
		document.location.href = 'wallet.html';
	});
	document.getElementById("btnSave").addEventListener("tap", function() {
		var util = new Util();
		var money = document.getElementById("txtMoney").value;
		var openid = util.getvalueincache("USER_OPENID");
		if(money<=0){
			mui.toast("请输入正确的金额");
			return;
		}
		if (util.isNullStr(openid)) {
//			document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3fc8241ec665d2e0&redirect_uri=http%3A%2F%2Fapp.youbanghulian.com%2FPayment%2FWX%2Fwxpayback.php&response_type=code&scope=snsapi_userinfo&state=" + orderid + "-" + money + "-" + couponid + "#wechat_redirect";
		} else {
			var params = {};
			//			var util = new Util();
			params.userid = util.getvalueincache("USER_ID");
			params.openid = util.getvalueincache("USER_OPENID");
			params.money = money;
			
			var temp = document.getElementById("dynamicForm");
			temp.setAttribute("method", 'post');
			temp.setAttribute("action", "/Payment/WX/gotopay.php");
			temp.innerHTML = "";
			var opt = document.createElement("input");
			opt.setAttribute("type", "hidden");
			opt.setAttribute("name", "payuserid");
			opt.setAttribute("value", params.userid);
			temp.appendChild(opt);
			var opt2 = document.createElement("input");
			opt2.setAttribute("type", "hidden");
			opt2.setAttribute("name", "paymoney");
			opt2.setAttribute("value", params.money);
			temp.appendChild(opt2);
			var opt3 = document.createElement("input");
			opt3.setAttribute("type", "hidden");
			opt3.setAttribute("name", "payopenid");
			opt3.setAttribute("value", params.openid);
			temp.appendChild(opt3);
			
			temp.submit();
		}
	});
});