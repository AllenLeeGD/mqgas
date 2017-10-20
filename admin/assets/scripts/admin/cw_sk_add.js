
function bulidData() {
	var util = new Util();
	var opttime = $("#opttime").val();
	if(util.isNullStr(send_obj.cname) || util.isNullStr(send_obj.membercode) || util.isNullStr(send_obj.totalmoney) || util.isNullStr(opttime)){
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
	send_obj.opttime = $("#opttime").val();
	if(obj != false) {
		util.showLoading();
		var url = "/Mq/CW/savecwsk";		
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
	send_obj.cname="";
	send_obj.membercode="";
	send_obj.opttime="";
	send_obj.bankmsg="";
	send_obj.remark="";
	send_vue = new Vue({
		el:"#form_app",
		data:{sendobj:send_obj}
	});
	
}

$(document).ready(function() {
	
	if(jQuery().datepicker) {
		$('.date-picker').datepicker({
			rtl: App.isRTL(),
			autoclose: true
		});
		$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	}
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	
});