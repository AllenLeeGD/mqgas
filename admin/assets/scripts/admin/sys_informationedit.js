function resetForm(){
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)){
		start = 0;
	}
	window.location.href = "sys_informationlist.php?tag=sysadmin&item=2&start="+start+"&params="+params;
}
function bulidData(){
	var util = new Util();
	var title = $("#title").val().trim();
	var remark = $("#remark").val().trim();
	var headicondata = $("#headicondata").val();
	var headicon = $("#headicon").val();
	for(var instanceName in CKEDITOR.instances) {
        CKEDITOR.instances[instanceName].updateElement();
    }
	var content = CKEDITOR.instances.content.getData();
	var sortno = $("#sortno").val().trim();
	var status = $("#status").val().trim();
	var bl = true;
	if(util.isNullStr(title)){
		$('#titlegroup').removeClass('has-success');
		$('#titlegroup').addClass('has-error');
		$('#titlesuc').hide();
		$('#titleerr').show();
		bl = false;
	}else{
		$('#titlegroup').removeClass('has-error');
		$('#titlegroup').addClass('has-success');
		$('#titleerr').hide();
		$('#titlesuc').show();
	}
	if(util.isNullStr(remark)){
		$('#remarkgroup').removeClass('has-success');
		$('#remarkgroup').addClass('has-error');
		$('#remarksuc').hide();
		$('#remarkerr').show();
		bl = false;
	}else{
		$('#remarkgroup').removeClass('has-error');
		$('#remarkgroup').addClass('has-success');
		$('#remarkerr').hide();
		$('#remarksuc').show();
	}
	if(util.isNullStr(headicon) && util.isNullStr(headicondata)){
		$('#headicongroup').removeClass('has-success');
		$('#headicongroup').addClass('has-error');
		bl = false;
	}else{
		$('#headicongroup').removeClass('has-error');
		$('#headicongroup').addClass('has-success');
	}
	if(util.isNullStr(content)){
		$('#contentgroup').removeClass('has-success');
		$('#contentgroup').addClass('has-error');
		bl = false;
	}else{
		$('#contentgroup').removeClass('has-error');
		$('#contentgroup').addClass('has-success');
	}
	if(util.isNullStr(sortno)){
		sortno = "0";
	}
	if(util.isNullStr(status)){
		status = "0";
	}
	if(bl == false){
		$("#btnSave").button('reset');
		util.errorMsg('资料填写不完整，请核对');
		return false;
	}
	var obj = {};
	obj.title = title;
	obj.content = content;
	obj.remark = remark;
	obj.sortno = sortno;
	obj.imgpath = '';
	obj.status = status;
	return obj;
}
function saveData(){
	$("#btnSave").button('loading');
	var obj = bulidData();
	var util = new Util();
	util.showLoading();
	var pkid = util.getParam('pkid');
	if(obj != false){
		var headicondata = $("#headicondata").val();
		var headicon = $("#headicon").val();
		if(!util.isNullStr(headicon))
		{
			$.ajaxFileUpload({
				url: '../index.php/Admin/Admin/upload',
				type: 'post',
				secureuri: false, //一般设置为false
				fileElementId: 'headicon', // 上传文件的id、name属性名
				dataType: 'text',
				success: function(data, status) {
					obj.pkid = pkid;
					obj.imgpath = data;
					var content = JSON.stringify(obj);
					var objdata = {};
					objdata.content = base64_encode(encodeURI(content));
					var url = "/Admin/Admin/saveInformation/";
					util.postUrl(
						url,
						function(data, status) { //如果调用php成功  
							if (data=="1") {
									$("#btnSave").button("reset");
									util.hideLoading();
									util.successMsg('保存成功');
									resetForm();
							}else{
									$("#btnSave").button("reset");
									util.hideLoading();
									util.errorMsg('保存失败');
							}
						},
						objdata,
						function(XMLHttpRequest, textStatus, errorThrown) {
							util.errorMsg('内部服务器错误');
							util.hideLoading();
							$("#btnSave").button("reset");
						}
					);
				},
				error: function(data, status, e) {
					util.errorMsg(e);
					$("#btnSave").button("reset");
				}
			});
		}else{
			obj.pkid = pkid;
			obj.imgpath = headicondata;
			var content = JSON.stringify(obj);
			var objdata = {};
			objdata.content = base64_encode(encodeURI(content));
			util.showLoading();
			var url = "/Admin/Admin/saveInformation/";
			util.postUrl(
				url,
				function(data, status) { //如果调用php成功  
					if (data=="1") {
							$("#btnSave").button("reset");
							util.hideLoading();
							util.successMsg('保存成功');
							resetForm();
					}else{
							$("#btnSave").button("reset");
							util.hideLoading();
							util.errorMsg('保存失败');
					}
				},
				objdata,
				function(XMLHttpRequest, textStatus, errorThrown) {
					util.errorMsg('内部服务器错误');
					util.hideLoading();
					$("#btnSave").button("reset");
				}
			);
		}
		
	}
}
function loadData(pkid){
	var util = new Util();
	util.showLoading();
	util.getUrl("/Admin/Admin/findInformationByPkid/pkid/" + pkid, function(data, status) {
		try {
			var objdata = JSON.parse(data);
			var title = objdata[0].title;
			var remark = objdata[0].remark;
			var content = objdata[0].content;
			var sortno = objdata[0].sortno;
			var status = objdata[0].status;
			var headicon = objdata[0].imgpath;
			var headiconimg = "";
			if(!util.isNullStr(headicon)){
				headiconimg = "<img src=\""+ edu_host + "/Upload/" + headicon +"\" style=\"height:26px;\">";
			}
			$('#title').val(title);
			$('#remark').val(remark);
			//CKEDITOR.instances.content.setData(content);
			//document.getElementById('content').innerHTML = "";
			/*if (CKEDITOR.instances['content']){        
				CKEDITOR.remove(CKEDITOR.instances['content']);    
			}   
			var editor = CKEDITOR.replace('content');*/
			document.getElementById('content').innerHTML = content;
			if (CKEDITOR.instances['content']){        
				CKEDITOR.remove(CKEDITOR.instances['content']);    
			}   
			var editor = CKEDITOR.replace('content');
			$('#sortno').val(sortno);
			$('#status').val(status);
			$('#headicondata').val(headicon);
			$('#headiconimg').html("<a id=\"fbox_0\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + headicon+"\">"+headiconimg+"</a>&nbsp;&nbsp;上传封面图片文件（文件大小不超过2MB）");
		} catch (err) {
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
	if(util.isNullStr(pkid)){
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