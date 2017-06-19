
//校验
function validation() {
	var util = new Util();
	var realname = document.getElementById("realname").value;
	if (util.isNullStr(realname)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的真实姓名");
		return false;
	}
	var idno = document.getElementById("idno").value;
	if (util.isNullStr(idno)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的身份证号");
		return false;
	}else if(!IdentityCodeValid(idno)){
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />身份证号不正确");
		return false;
	}
	var mobile = document.getElementById("mobile").value;
	if (util.isNullStr(mobile)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的手机号");
		return false;
	}else if(!isMobile(mobile)){
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />手机号不正确");
		return false;
	}
	var content = document.getElementById("content").value;
	if (util.isNullStr(content)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的期待体验内容");
		return false;
	}
	return true;
}
function saveInfo(){
	var util = new Util();
	if(validation()){
		var userid = util.getvalueincache("USER_ID");
		//var userid = 'userid-01';
		var obj = {}
		obj.contactperson = document.getElementById("realname").value;
		obj.uname = document.getElementById("idno").value;
		obj.contact = document.getElementById("mobile").value;
		obj.content = document.getElementById("content").value;
		obj.userid = userid;
		mui.ajax(edu_host+'/index.php/Information/Information/addExperience', {
			data: obj,
			type: 'post',
			success: function(data) {
				if (data == "1") {
					mui.toast("<span class=\"mui-icon ion-ios-checkmark-outline\"></span><br />报名成功，我们会有专人与您联络");
				}else{
					mui.toast("<span class=\"mui-icon ion-ios-checkmark-outline\"></span><br />您已经报名过了");
				}
			}
		});
	}
}
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	document.getElementById("btnSave").addEventListener("tap", function() {
		saveInfo();
	});
});