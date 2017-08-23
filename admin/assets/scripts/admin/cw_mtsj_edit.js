
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
	send_obj.pkid = util.getParam("pkid");
	send_obj.type = util.getParam("type");
	if(obj != false) {
		util.showLoading();
		var url = "/Mq/CW/editcwmtsj";	
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

function loadData() {
	var util = new Util();	
	var pkid = util.getParam("pkid");
	var url = "/Mq/CW/loadcwmtsj/pkid/"+pkid;	
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				send_obj.bname=data.bname;
				send_obj.lname=data.lname;
				send_obj.snumber=data.snumber;
				send_obj.totalmoney=data.totalmoney;
				send_obj.msg=data.msg;
				send_obj.syqk=data.syqk;
				send_obj.status = data.status;
				send_obj.remark=data.remark;
				
				send_vue = new Vue({
					el:"#form_app",
					data:{sendobj:send_obj}
				});
				$("#opttime").val(new Date(data.opttime*1000).Format("yyyy-MM-dd"));
			},
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
				util.hideLoading();
				$("#btnSave").button("reset");
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
	
	var util = new Util();
	var showtype = util.getParam("showtype");
	var type = util.getParam("type");
	if(showtype=="display"){
		$("#btnSave").hide();
		$("#opttime").attr("disabled","disabled");
		$("#lname").attr("disabled","disabled");
		$("#bname").attr("disabled","disabled");
		$("#status").attr("disabled","disabled");
		$("#snumber").attr("disabled","disabled");
		$("#totalmoney").attr("disabled","disabled");
		$("#msg").attr("disabled","disabled");
		$("#syqk").attr("disabled","disabled");
		$("#opttime").attr("disabled","disabled");
		$("#remark").attr("disabled","disabled");
		if(type==0){
			$("#toptitle").html("财务码头收据(气款)管理");
			$("#ptitle").html("财务码头收据(气款)管理");
			$("#stitle").html("查看码头收据(气款)信息");
			$("#ltitle").html("财务码头收据(气款)管理");
			$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=2");
		}else{
			$("#toptitle").html("财务码头收据(押金)管理");
			$("#ptitle").html("财务码头收据(押金)管理");
			$("#stitle").html("编辑码头收据(押金)信息");
			$("#ltitle").html("财务码头收据(押金)管理");
			$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=3");
		}
	}else{
		if(type==0){
			$("#toptitle").html("财务码头收据(气款)管理");
			$("#ptitle").html("财务码头收据(气款)管理");
			$("#stitle").html("查看码头收据(气款)信息");
			$("#ltitle").html("财务码头收据(气款)管理");
			$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=2");
		}else{
			$("#toptitle").html("财务码头收据(押金)管理");
			$("#ptitle").html("财务码头收据(押金)管理");
			$("#stitle").html("编辑码头收据(押金)信息");
			$("#ltitle").html("财务码头收据(押金)管理");
			$("#ltitle").attr("href","cw_mtsj.php?tag=cwadmin&item=3");
		}
		$("#btnSave").bind('click', function() {
			saveData();
		});
	}
	
});