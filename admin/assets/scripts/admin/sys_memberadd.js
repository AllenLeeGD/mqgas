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
	var code = $("#code").val().trim();
	var storename = $("#storename").val().trim();
	var membertype = $("#membertype").find("option:selected").val();
	var detailtype = $("#detailtype").find("option:selected").val();
	var mobile = $("#mobile").val().trim();
	var address = $("#address").val().trim();
	var level = $("#type").find("option:selected").val();
	var yewuid = $("#yewuid").find("option:selected").val();
	var yewuname = $("#yewuid").find("option:selected").html();
	var yue = $("#yue").val();
	var zhangqi = $("#zhangqi").val();
	if(util.isNullStr(realname) || util.isNullStr(mobile) || util.isNullStr(address) || util.isNullStr(level)) {
		return false;
	}
	var obj = {};
	obj.realname = realname;
	obj.code = code;
	obj.storename = storename;
	obj.membertype = membertype;
	obj.detailtype = detailtype;
	obj.address = address;
	obj.mobile = mobile;
	obj.level = level;
	obj.yewuid = yewuid;
	obj.yewuname = yewuname;
	obj.yue = yue;
	obj.zhangqi = zhangqi;
	return obj;
}

function saveData() {
	$("#btnSave").button('loading');
	var obj = bulidData();
	var util = new Util();
	if(obj == false) {
		util.errorMsg("请填写完整资料");
		$("#btnSave").button("reset");
		return;
	}
	util.showLoading();
	var pkid = util.getParam('pkid');
	obj.pkid = pkid
	var url = "/Mq/Member/addMember";
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
	util.getUrl("/Mq/Member/findSetting", function(s_data, s_status) {
		var result = "";
		for(var j = 0; j < s_data.length; j++) {
			result += "<option value=\"" + s_data[j].pkid + "\">" + s_data[j].levelname + "</option>";			
		}
		document.getElementById("type").innerHTML = result;
	});
	util.getUrl("/Mq/Daily/loadyewu", function(s_data, s_status) {
		var result = "<option value=\"\">无</option>";
		for(var j = 0; j < s_data.length; j++) {
			result += "<option value=\"" + s_data[j].pid + "\">" + s_data[j].realname + "</option>";			
		}
		document.getElementById("yewuid").innerHTML = result;
	});
}
$(document).ready(function() {
	var util = new Util();

	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});