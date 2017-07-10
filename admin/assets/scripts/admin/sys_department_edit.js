
function bulidData() {
	var util = new Util();
	if(util.isNullStr(send_obj.pname) || util.isNullStr(send_obj.did) ){
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
		var url = "/Mq/Role/editdepartmentdetail";
		send_obj.dname=$("#dname").find("option:selected").text();
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
					util.successMsg('保存成功');
				} else {
					$("#btnSave").button("reset");
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
function loadoption(){
	var url = "/Mq/Role/loaddepartment";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.options=data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
}
function loadData() {
	var util = new Util();	
	var pkid = util.getParam("pkid");
	var url = "/Mq/Role/loaddepartmentdetail/pkid/"+pkid;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_obj = data;
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:send_obj,options:[]}
			});
			loadoption();
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}

$(document).ready(function() {
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	
});