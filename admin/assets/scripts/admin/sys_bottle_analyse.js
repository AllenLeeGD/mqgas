var send_obj = {};
function bulidData(){
	var util = new Util();
	var startdate = $("#startdate").val();
	var enddate = $("#enddate").val();
	var departmentid = $("#departmentid").val();
	if(util.isNullStr(startdate) || util.isNullStr(enddate) || util.isNullStr(departmentid)){
		return false;
	}
	send_obj.startdate = startdate;
	send_obj.enddate = enddate;
	return true;
}

function saveData(){
	var util = new Util();
	$("#btnSave").button('loading');
	var obj = bulidData();
	if(obj==false){
		$("#btnSave").button("reset");
		util.errorMsg('请填写完整信息');
		return;
	}
	
	if(obj != false){
		util.showLoading();
		var form = $("<form>"); //定义一个form表单
		form.attr("style", "display:none");
		form.attr("target", "");
		form.attr("method", "post");
		form.attr("action", "/index.php/Mq/Bottle/analyse/departmentid/"+send_obj.departmentid+"/startdate/"+send_obj.startdate+"/enddate/"+send_obj.enddate);
		var input1 = $("<input>");
		input1.attr("type", "hidden");
		input1.attr("name", "exportData");
		input1.attr("value", (new Date()).getMilliseconds());
		$("body").append(form); //将表单放置在web中
		form.append(input1);
		util.hideLoading();
		form.submit(); //表单提交 
	}
}
function jpmxData(){
	var util = new Util();
//	$("#btnJpmx").button('loading');
	var obj = bulidData();
	if(obj==false){
//		$("#btnJpmx").button("reset");
		util.errorMsg('请填写完整信息');
		return;
	}
	
	if(obj != false){
		util.showLoading();
		var form = $("<form>"); //定义一个form表单
		form.attr("style", "display:none");
		form.attr("target", "");
		form.attr("method", "post");
		form.attr("action", "/index.php/Mq/Bottle/analysejpmx/departmentid/"+send_obj.departmentid+"/startdate/"+send_obj.startdate+"/enddate/"+send_obj.enddate);
		var input1 = $("<input>");
		input1.attr("type", "hidden");
		input1.attr("name", "exportData");
		input1.attr("value", (new Date()).getMilliseconds());
		$("body").append(form); //将表单放置在web中
		form.append(input1);
		util.hideLoading();
		form.submit(); //表单提交 
	}
}

$(document).ready(function() {	
	var util = new Util();
	var url = "/Mq/Daily/loaddepartment";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:send_obj,ds:data}				
			});
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
	$("#btnPfmx").bind('click', function() {
		saveData();
	});
	$("#btnJpmx").bind('click', function() {
		jpmxData();
	});
});