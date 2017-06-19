function findMemberByPkid() {
	var util = new Util();
	var pkid = util.getvalueincache("USERID");
	util.loadingmessage('加载中...');
	mui.ajax(edu_host + '/index.php/Mq/Member/loadAccount/pkid/' + pkid, {
		type: 'post',
		success: function(data) {
			util.loadingEnd();
			if(data) {
				if(data.headicon.indexOf("http") != -1){
					document.getElementById("headicon").src = data.headicon;
				}else{
					document.getElementById("headicon").src = edu_host + "/Upload/" + data.headicon;	
				}
				document.getElementById("realname").innerHTML = data.realname;
				document.getElementById("ordercount").innerHTML = data.ordercount;
			} else {
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />加载出错");
			}
		},
		complete: function() {

		}
	});
	mui.ajax(edu_host + '/index.php/Mq/Member/loadSetting/pkid/' + pkid, {
		type: 'post',
		success: function(data) {
			if(data) {
				document.getElementById("levelname").innerHTML = data.levelname;
			} 
		}
	});
}
document.getElementById("aHome").addEventListener("tap", function() {
	document.location.href = "index.html";
});

document.getElementById("aSetting").addEventListener("tap", function() {
	document.location.href = "setting.html";
});

document.getElementById("aCoupon").addEventListener("tap", function() {
	document.location.href = "coupon.html";
});

document.getElementById("aGetCoupon").addEventListener("tap", function() {
	document.location.href = "couponget.html";
});

document.getElementById("aOrder").addEventListener("tap", function() {
	document.location.href = "order.html";
});
document.getElementById("aFinished").addEventListener("tap",function(){
	document.location.href = "order.html?ordertype=finished";
});
document.getElementById("aGoing").addEventListener("tap",function(){
	document.location.href = "order.html?ordertype=going";
});
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	var util = new Util();
	var userid = util.getvalueincache("USERID");
	if(userid==null || userid=="" || userid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
	findMemberByPkid();
});