
function loadinfo(){
	var util = new Util();
	var userid = util.getvalueincache("USERID");
	if(userid==null || userid=="" || userid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
	var openid = util.getvalueincache("OPENID");
	if(openid==null || openid=="" || openid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxbindback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
	
}
function checknull(){
	var util = new Util();
	var mobile = document.getElementById("mobile").value;
	if(util.isNullStr(mobile)){
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写联系电话");
		return false;
	}else{
		return true;
	}
	
}
document.getElementById("btnSave").addEventListener("tap", function() {
	var util = new Util();
	if(checknull()) {
		var mobile = document.getElementById("mobile").value;
		var openid = util.getvalueincache("OPENID");		
		document.getElementById("btnSave").disabled=true;
		mui.ajax(edu_host + '/index.php/Member/Reg/checkbind/openid/'+openid+"/mobile/"+mobile, {
			type: 'post',
			success: function(data) {
				if(data=="yes"){
					document.location.href = "bindsuccess.html";
				}else{
					mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />没有此号码");
					document.getElementById("btnSave").disabled=false;
				}
			}
		});
	}
});
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