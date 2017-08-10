
function bulidData() {
	var util = new Util();
	if(util.isNullStr(send_obj.name) || util.isNullStr(send_obj.realname) || util.isNullStr(send_obj.mobile) || util.isNullStr(send_obj.worknumber) || util.isNullStr(send_obj.password)){
		return false;
	}
	return true;
}

function saveData() {
	var util = new Util();
	$("#btnSave").button('loading');
	var obj = bulidData();
	if(obj == false) {
		$("#btnSave").button("reset");
		util.errorMsg('请填写完整信息');
		return;
	}

	if(obj != false) {
		util.showLoading();
		var url = "/Mq/Role/saveUserSetting";
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
					util.successMsg('保存成功');
					setTimeout(function(){
						document.location.reload();
					},1000);
				} else {
					$("#btnSave").button("reset");
					util.hideLoading();
					util.errorMsg('保存失败');
				}
			},
			send_obj,
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
				util.hideLoading();
				$("#btnSave").button("reset");
			}
		);
	}
}
var send_obj = {};
var send_vue;

function loadData() {
	var util = new Util();
	var roletype = util.getParam("roletype");
	if(roletype == "biz") {
		$("#b_title").html("业务");
		$("#s_title").html("业务");
		send_obj.role=2;
	} else if(roletype == "huawu") {
		$("#b_title").html("话务");
		$("#s_title").html("话务");
		send_obj.role=1;
	} else if(roletype == "caiwu") {
		$("#b_title").html("财务");
		$("#s_title").html("财务");
		send_obj.role=3;
	} else if(roletype == "piaofang") {
		$("#b_title").html("票房");
		$("#s_title").html("票房");
		send_obj.role=4;
	} else if(roletype == "siji") {
		$("#b_title").html("司机");
		$("#s_title").html("司机");
		send_obj.role=5;
	} else if(roletype == "songqi") {
		$("#b_title").html("送气工");
		$("#s_title").html("送气工");
		send_obj.role=6;
	} else if(roletype == "yayun") {
		$("#b_title").html("押运");
		$("#s_title").html("押运");
		send_obj.role=7;
	} else if(roletype == "yingye") {
		$("#b_title").html("营业");
		$("#s_title").html("营业");
		send_obj.role=8;
	} else if(roletype == "chedui") {
		$("#b_title").html("车队负责人");
		$("#s_title").html("车队负责人");
		send_obj.role=9;
	}
	send_obj.name="";
	send_obj.realname="";
	send_obj.mobile="";
	send_obj.worknumber="";
	send_obj.email="";
	send_obj.password="";
}

$(document).ready(function() {
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	send_vue = new Vue({
		el:"#form_app",
		data:send_obj
	});
});