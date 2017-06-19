mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	document.getElementById("aTel").addEventListener("tap", function(e) {
		document.location.href = "tel:13169666888";
	});
	var util = new Util();
	var usertype = util.getvalueincache("USER_TYPE");
	if (usertype == user_type_normal) {
		document.getElementById("btnSave").disabled = true;
		document.getElementById("btnSave").innerHTML = "您已是认证会员";
	} else if (usertype == user_type_vip) {
		document.getElementById("btnSave").disabled = true;
		document.getElementById("btnSave").innerHTML = "您已是尊贵会员";
	} else if (usertype == user_type_new || usertype == user_type_refuse) {
		document.getElementById("btnSave").disabled = false;
		document.getElementById("btnSave").innerHTML = "去认证";
		document.getElementById("btnSave").addEventListener("tap", function(e) {
			document.location.href = "setting.html";
		});
	} else if (usertype == user_type_verifying) {
		document.getElementById("btnSave").disabled = true;
		document.getElementById("btnSave").innerHTML = "认证中...";
	} else {
		document.getElementById("btnSave").disabled = false;
		document.getElementById("btnSave").innerHTML = "返回首页";
		document.getElementById("btnSave").addEventListener("tap", function(e) {
			document.location.href = "index.html";
		});
	}
});