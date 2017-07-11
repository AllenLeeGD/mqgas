
function bulidData() {
	var util = new Util();
	if(util.isNullStr(send_obj.carnumber) || util.isNullStr(send_obj.type) ){
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
		var url = "/Mq/Role/savecar";		
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
					loadData();
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

function loadData() {
	var util = new Util();	
	send_obj.carnumber="";
	send_obj.type="";
	send_obj.remark="";
	send_vue = new Vue({
		el:"#form_app",
		data:{sendobj:send_obj}
	});
	
}

$(document).ready(function() {
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	
});