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
		form.attr("action", "/index.php/Mq/Bottle/jxcq/departmentid/"+send_obj.departmentid+"/startdate/"+send_obj.startdate+"/enddate/"+send_obj.enddate);
		var input1 = $("<input>");
		input1.attr("type", "hidden");
		input1.attr("name", "exportData");
		input1.attr("value", (new Date()).getMilliseconds());
		
		var input2 = $("<input>");
		input2.attr("type", "hidden");
		input2.attr("name", "qx");
		input2.attr("name", "qx");
		input2.attr("value", $("#qx").val());
		
		var input3 = $("<input>");
		input3.attr("type", "hidden");
		input3.attr("name", "yx");
		input3.attr("name", "yx");
		input3.attr("value", $("#yx").val());
		
		var input4 = $("<input>");
		input4.attr("type", "hidden");
		input4.attr("name", "jf");
		input4.attr("name", "jf");
		input4.attr("value", $("#jf").val());
		
		var input5 = $("<input>");
		input5.attr("type", "hidden");
		input5.attr("name", "zf");
		input5.attr("name", "zf");
		input5.attr("value", $("#zf").val());
		
		var input6 = $("<input>");
		input6.attr("type", "hidden");
		input6.attr("name", "twokg");
		input6.attr("name", "twokg");
		input6.attr("value", $("#twokg").val());
		
		var input7 = $("<input>");
		input7.attr("type", "hidden");
		input7.attr("name", "fivekg");
		input7.attr("name", "fivekg");
		input7.attr("value", $("#fivekg").val());
		
		$("body").append(form); //将表单放置在web中
		form.append(input1);
		form.append(input2);
		form.append(input3);
		form.append(input4);
		form.append(input5);
		form.append(input6);
		form.append(input7);
		util.hideLoading();
		form.submit(); //表单提交 
		$("#btnSave").button("reset");
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
	
	$("#btnSave").bind('click', function() {
		saveData();
	});
});