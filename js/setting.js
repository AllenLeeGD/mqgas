var imgpath;
/**
 * 上传用户头像. 
 */
function uploadHead() {
	var img = $("#upfile").val();
	var util = new Util();
	var sReturn = checkpicAndSize(img, 'upfile');
	if(sReturn == "picfalse") {
		mui.toast("上传的图片格式不正确");
		return;
	} else if(sReturn == "sizefalse") {
		mui.toast("上传的图片不能超过10MB");
		return;
	}
	util.loadingmessage("上传中...");
	$.ajaxFileUpload({
		url: edu_host + '/index.php/Admin/Admin/upload',
		type: 'post',
		secureuri: false, //一般设置为false
		fileElementId: 'upfile', // 上传文件的id、name属性名
		dataType: 'text',
		success: function(data, status) {
			if(data == "上传内容不符合要求") {
				mui.toast("上传内容不符合要求");
				$("#head_div").html("<input id=\"upfile\" name=\"upfile\" type=\"file\" />");
				document.getElementById("upload").addEventListener("tap", function() {
					uploadHead();
				});
				return;
			}
			imgpath = data;
			$("#upload_data").data('headicon', data);
			$("#headicon").attr('src', edu_host + "/Upload/" + data);
			$("#head_div").html("<input id=\"upfile\" name=\"upfile\" type=\"file\" />");
			document.getElementById("upfile").addEventListener("change", function() {
				uploadHead();
			});
		},
		error: function(data, status, e) {
			alert(e);
		},
		complete: function() {
			util.loadingEnd();
		}
	});
}
//校验
function validation() {
	var util = new Util();
	var realname = document.getElementById("realname").value;
	if(util.isNullStr(realname.trim())) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的姓名");
		return false;
	}
	var address = document.getElementById("address").value;
	if(util.isNullStr(address.trim())) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的送货地址");
		return false;
	}
	var mobile = document.getElementById("mobile").value;
	if(util.isNullStr(mobile.trim())) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写您的联系电话");
		return false;
	}
	return true;
}
document.getElementById("upfile").addEventListener("change", function() {
	uploadHead();
});
document.getElementById("upload").addEventListener("tap",function(){
	document.getElementById("upfile").click();
});
function saveInfo() {
	var util = new Util();
	if(validation()) {
		var pkid = util.getvalueincache("USERID");
		//var pkid = 'userid-01';
		var obj = {}
		obj.realname = document.getElementById("realname").value;
		obj.address = document.getElementById("address").value;
		obj.mobile = document.getElementById("mobile").value;
		obj.headicon = imgpath;
		obj.pkid = pkid;
		mui.ajax(edu_host + '/index.php/Mq/Member/saveSetting', {
			data: obj,
			type: 'post',
			success: function(data) {
				if(data == "yes") {
					mui.toast("<span class=\"mui-icon ion-ios-checkmark-outline\"></span><br />保存成功");
					findMemberByPkid();
				}
			}
		});
	}
}

function findMemberByPkid() {
	var util = new Util();
	var pkid = util.getvalueincache("USERID");
	util.loadingmessage('加载中...');
	mui.ajax(edu_host + '/index.php/Mq/Member/loadSetting/pkid/' + pkid, {
		type: 'post',
		success: function(data) {
			if(data) {
				if(data.headicon.indexOf("http") != -1){
					document.getElementById("headicon").src = data.headicon;
				}else{
					document.getElementById("headicon").src = edu_host + "/Upload/" + data.headicon;	
				}
				document.getElementById("realname").value = data.realname;
				document.getElementById("mobile").value = data.mobile;
				document.getElementById("address").value = data.address;
				document.getElementById("levelname").innerHTML = data.levelname;
				imgpath = data.headicon;
			} else {
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />加载出错");
			}
		},
		complete: function() {
			util.loadingEnd();
		}
	});
}
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	findMemberByPkid();
	document.getElementById("btnSave").addEventListener("tap", function() {
		saveInfo();
	});
});