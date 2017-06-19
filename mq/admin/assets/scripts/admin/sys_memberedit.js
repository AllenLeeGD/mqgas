function resetForm() {
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)) {
		start = 0;
	}
	window.location.href = "sys_memberlist.php?tag=memberadmin&item=1&start=" + start + "&params=" + params;
}

function bulidData() {
	var util = new Util();
	var realname = $("#realname").val().trim();
	var mobile = $("#mobile").val().trim();
	var address = $("#address").val().trim();
	var level = $("option:selected").val()
	if(util.isNullStr(realname) || util.isNullStr(mobile) || util.isNullStr(address) || util.isNullStr(level)) {
		return false;
	}
	var obj = {};
	obj.realname = realname;
	obj.address = address;
	obj.mobile = mobile;
	obj.level = level;
	return obj;
}

function saveData() {
	$("#btnSave").button('loading');
	var obj = bulidData();
	var util = new Util();
	if(obj==false){
		util.errorMsg("请填写完整资料");
		$("#btnSave").button("reset");
		return;
	}
	util.showLoading();
	var pkid = util.getParam('pkid');
	obj.pkid = pkid
	var url = "/Mq/Member/saveMember";
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			if(data == "yes") {
				$("#btnSave").button("reset");
				util.hideLoading();
				util.successMsg('保存成功');
				resetForm();
			} else {
				$("#btnSave").button("reset");
				util.hideLoading();
				util.errorMsg('保存失败');
			}
		},
		obj,
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
			$("#btnSave").button("reset");
		}
	);
}

function loadData(pkid) {
	var util = new Util();
	util.showLoading();
	var typeid = "";
	util.getUrl("/Mq/Member/findMemberByPkid/pkid/" + pkid, function(data, status) {
		try {
			var objdata = JSON.parse(data);
			var realname = objdata[0].realname;
			var mobile = objdata[0].mobile;
			var address = objdata[0].address;
			var level = objdata[0].level;
			$('#realname').val(realname);
			$('#mobile').val(mobile);
			$('#address').val(address);
			util.getUrl("/Mq/Member/findSetting", function(s_data, s_status) {
				var result = "";
				for(var j = 0;j<s_data.length;j++){
					if(s_data[j].pkid==level){
						result += "<option selected value=\""+s_data[j].pkid+"\">"+s_data[j].levelname+"</option>";	
					}else{
						result += "<option value=\""+s_data[j].pkid+"\">"+s_data[j].levelname+"</option>";	
					}					
				}
				document.getElementById("type").innerHTML=result;
			});
		} catch(err) {
			util.errorMsg('找不到该记录');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
}
$(document).ready(function() {
	var util = new Util();
	var pkid = util.getParam('pkid');
	if(util.isNullStr(pkid)) {
		util.errorMsg('缺少必要参数:pkid');
		return;
	}
	loadData(pkid);
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});