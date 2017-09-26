
function bulidData() {
	var util = new Util();
	if(util.isNullStr($("#dailydate").val()) || util.isNullStr(send_obj.did) || util.isNullStr(send_obj.pid) || util.isNullStr(send_obj.carid) || util.isNullStr(send_obj.sid) || util.isNullStr(send_obj.yid)){
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
		var url = "/Mq/Daily/savecarsdaily";	
		send_obj.dailydate = $("#dailydate").val();
		send_obj.dname=$("#did").find("option:selected").text();
		send_obj.pname=$("#pid").find("option:selected").text();
		send_obj.carnumber=$("#carid").find("option:selected").text();
		send_obj.sname=$("#sid").find("option:selected").text();
		send_obj.yname=$("#yid").find("option:selected").text();
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
function loadcars() {
	var util = new Util();	
	var url = "/Mq/Daily/loadcars";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.cars = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}
function loadsijis() {
	var util = new Util();	
	var url = "/Mq/Daily/loadsiji";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.sijis = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}
function loadyayuns() {
	var util = new Util();	
	var url = "/Mq/Daily/loadyayun";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue.$data.yayuns = data;
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}
function loadData() {
	var util = new Util();	
	send_obj.carcourse="";
	send_obj.oilprice="";
	send_obj.cost="";
	send_obj.dailydate="";
	send_obj.dailyrun="";
	send_obj.remark="";
	var url = "/Mq/Daily/loaddepartment";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:send_obj,departments:data,pianqus:[],cars:[],sijis:[],yayuns:[]},
				methods:{
					getpianqu:function(){
						var did = $("#did").val();
						if(!util.isNullStr(did)){
							util.postUrl(
								"/Mq/Daily/loadpianqu/did/"+did,
								function(pianqus, status) { //如果调用php成功  
									send_vue.$data.pianqus = pianqus;
								},
								function(XMLHttpRequest, textStatus, errorThrown) {
									
								}
							);
						}
					}
				}
			});
			loadcars();
			loadsijis();
			loadyayuns();
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