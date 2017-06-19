function resetForm(){
	$("#title").val('');
	$("#headicon").val('');
	CKEDITOR.instances.content.setData('');
	$("#sortno").val('');
	$("#status").val('');
	$('#titlegroup').removeClass('has-success');
	$('#titlegroup').removeClass('has-error');
	$('#titlegroup').removeClass('has-success');
	$('#titlegroup').removeClass('has-error');
	$('#titlesuc').hide();
	$('#titleerr').hide();
	$('#contentgroup').removeClass('has-success');
	$('#contentgroup').removeClass('has-error');
	$('#headicongroup').removeClass('has-success');
	$('#headicongroup').removeClass('has-error');
	$('#remarkgroup').removeClass('has-success');
	$('#remarkgroup').removeClass('has-error');
	$('#remarksuc').hide();
	$('#remarkerr').hide();
}
function bulidData(){
	var util = new Util();
	var title = $("#title").val().trim();
	var remark = $("#remark").val().trim();
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
	if(util.isNullStr(headicon)){
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
	var util = new Util();
	var obj = bulidData();
	if(obj != false){
		$.ajaxFileUpload({
			url: '../index.php/Admin/Admin/upload',
			type: 'post',
			secureuri: false, //一般设置为false
			fileElementId: 'headicon', // 上传文件的id、name属性名
			dataType: 'text',
			success: function(data, status) {
				obj.imgpath = data;
				var content = JSON.stringify(obj);
				var objdata = {};
				objdata.content = base64_encode(encodeURI(content));
				util.showLoading();
				var url = "/Admin/Admin/addInformation/";
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
		
	}
}
$(document).ready(function() {
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});