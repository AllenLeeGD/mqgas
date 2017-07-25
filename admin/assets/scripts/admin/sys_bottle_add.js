function bulidData() {
	var util = new Util();
	if(util.isNullStr($("#departmentid").val()) || util.isNullStr($("#changetype").val()) || util.isNullStr($("#type").val())) {
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
		var url = "/Mq/Bottle/savebottle";
		send_obj.pname = $("#pid").find("option:selected").text();
		send_obj.jname = $("#jid").find("option:selected").text();
		send_obj.fname = $("#fid").find("option:selected").text();
		send_obj.rname = $("#rid").find("option:selected").text();
		send_obj.deparmentname = $("#departmentid").find("option:selected").text();
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if(data == "yes") {
					$("#btnSave").button("reset");
					util.hideLoading();
					util.successMsg('保存成功');
					setTimeout(function() {
						document.location.reload();
					}, 1000);
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

function loadjiekou() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/1/type/2";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.jies = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {

		}
	);
}

function loadqiti() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/1/type/3";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.qis = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {

		}
	);
}

function loadranqi() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/1/type/4";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.rans = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {

		}
	);
}
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
function loadpeijian() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/2/type/6";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.fs = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {

		}
	);
}

function loadData() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/1/type/1";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  			
			send_vue = new Vue({
				el: "#form_app",
				data: {
					sendobj: send_obj,pings:data,jies:[],rans:[],options:[],fs:[]
				}
			});
			loadjiekou();
			loadranqi();
			loadqiti();
			loadpeijian();
			loadoption();
			var memberid = util.getParam("memberid");
			var membername = util.getParam("membername");
			var mobile = util.getParam("mobile");
			send_vue.$data.sendobj.memberid = memberid;
			send_vue.$data.sendobj.membername = base64_decode(membername);
			send_vue.$data.sendobj.mobile = mobile;
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