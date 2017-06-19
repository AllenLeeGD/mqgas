
function loadinfo(){
	var util = new Util();
	var userid = util.getvalueincache("USERID");
	if(userid==null || userid=="" || userid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
	util.loadingmessage('加载中...');
	mui.ajax(edu_host + '/index.php/Mq/Member/loadSetting/pkid/' + userid, {
		type: 'post',
		success: function(data) {
			if(data) {
				document.getElementById("realname").value = data.realname;
				document.getElementById("mobile").value = data.mobile;
				document.getElementById("address").value = data.address;
				
			} else {
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />加载出错");
			}
		},
		complete: function() {
			util.loadingEnd();
		}
	});
}

mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	var util = new Util();
	var userid = util.getParam("userid");
	if(!util.isNullStr(userid)){
		
		util.putvalueincache("USERID",userid);
		var openid = util.getParam("openid");
		util.putvalueincache("OPENID",openid);
	}
	loadinfo();
});