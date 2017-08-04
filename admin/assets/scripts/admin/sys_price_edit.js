function bulidData() {
	var util = new Util();
	if(util.isNullStr($("#name").val()) || util.isNullStr(send_obj.price) || util.isNullStr(send_obj.pid) || util.isNullStr(send_obj.ordershow) || util.isNullStr(send_obj.type)) {
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
		var pkid = util.getParam("pkid");
		var url = "/Mq/Price/editprice/pkid/"+pkid;
		send_obj.pname = $("#pid").find("option:selected").text();
		send_obj.jname = $("#jid").find("option:selected").text();
		send_obj.qname = $("#qid").find("option:selected").text();
		send_obj.rname = $("#rid").find("option:selected").text();
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

function loadping() {
	var util = new Util();
	var url = "/Mq/Price/loadgastype/classify/1/type/1";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.pings = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {

		}
	);
}

function loadData() {
	var util = new Util();
	var pkid = util.getParam("pkid");
	var url = "/Mq/Price/loadprice/pkid/"+pkid;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_obj = data;
			send_vue = new Vue({
				el: "#form_app",
				data: {
					sendobj: send_obj,pings:[],jies:[],qis:[],rans:[]
				}
			});
			loadjiekou();
			loadranqi();
			loadqiti();
			loadping();
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