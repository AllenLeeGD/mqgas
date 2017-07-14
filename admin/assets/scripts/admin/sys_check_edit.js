
function bulidData() {
	var util = new Util();
	if(util.isNullStr($("#checkdate").val()) || util.isNullStr(send_obj.remark)){
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
		var url = "/Mq/Check/editcheck";	
		send_obj.checkdate = $("#checkdate").val();
		send_obj.memberid = util.getParam("memberid");
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
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
function loadcars() {
	var util = new Util();	
	var url = "/Mq/Check/loadcheck";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.cars = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}

function loadData() {
	var util = new Util();	
	send_obj.remark="";
	var pkid = util.getParam("pkid");
	util.postUrl(
		"/Mq/Check/loadcheck/pkid/"+pkid,
		function(data, status) { //如果调用php成功  
			$("#checkdate").val(data.checkdate);
			send_obj = data;
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:data}
			});		
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
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