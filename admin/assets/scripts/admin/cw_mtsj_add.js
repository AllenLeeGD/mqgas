
function bulidData() {
	var util = new Util();
	var opttime = $("#opttime").val();
	if(util.isNullStr(send_obj.bname) || util.isNullStr(send_obj.totalmoney) || util.isNullStr(send_obj.snumber) || util.isNullStr(send_obj.lname) || util.isNullStr(opttime)){
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
	send_obj.type = util.getParam("type");
	if(obj != false) {
		util.showLoading();
		var url = "/Mq/CW/savecwmtsj";		
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
	send_obj.bname="";
	send_obj.lname="";
	send_obj.status="0";
	send_obj.snumber="";
	send_obj.totalmoney="";
	send_obj.opttime="";
	send_obj.msg="";
	send_obj.syqk="";
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
	var util = new Util();
	var type = util.getParam('type');
	if(type==0){
		$("#toptitle").html("财务码头收据(气款)管理");
		$("#ptitle").html("财务码头收据(气款)管理");
		$("#stitle").html("增加码头收据(气款)信息");
		$("#ltitle").html("财务码头收据(气款)管理");
		$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=2");
	}else{
		$("#toptitle").html("财务码头收据(押金)管理");
		$("#ptitle").html("财务码头收据(押金)管理");
		$("#stitle").html("增加码头收据(押金)信息");
		$("#ltitle").html("财务码头收据(押金)管理");
		$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=3");
	}
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	
});