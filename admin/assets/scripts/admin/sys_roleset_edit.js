function bulidData() {
	var util = new Util();
	if(util.isNullStr(send_obj.name) || util.isNullStr(send_obj.realname) || util.isNullStr(send_obj.mobile) || util.isNullStr(send_obj.worknumber)) {
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
		var url = "/Mq/Role/editUserSetting";
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
					load(false);
					util.successMsg('保存成功');
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

function load(init) {
	var util = new Util();
	var pid = util.getParam("pid");
	var url = "/Mq/Role/loadUserSetting/pid/" + pid;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			if(data) {
				send_obj = data;
				if(init) {
					send_vue = new Vue({
						el: "#form_app",
						data: send_obj
					});
				}
				if(data.role == 2) {
					$("#b_title").html("业务");
					$("#s_title").html("业务");
				} else if(data.role == 1) {
					$("#b_title").html("话务");
					$("#s_title").html("话务");
				} else if(data.role == 3) {
					$("#b_title").html("财务");
					$("#s_title").html("财务");
				} else if(data.role == 4) {
					$("#b_title").html("票房");
					$("#s_title").html("票房");
				} else if(data.role == 5) {
					$("#b_title").html("司机");
					$("#s_title").html("司机");
				} else if(data.role == 6) {
					$("#b_title").html("送气工");
					$("#s_title").html("送气工");
				}
			} else {
				util.errorMsg('加载失败');
			}
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
		}
	);
}

$(document).ready(function() {
	load(true);
	$("#btnSave").bind('click', function() {
		saveData();
	});

});