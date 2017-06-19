function resetForm(){
	$("#name1").val('');
	$('#name1group').removeClass('has-success');
	$('#name1group').removeClass('has-error');
}
function bulidData(){
	var util = new Util();
	var name = $("#name1").val().trim();
	var bl = true;
	if(util.isNullStr(name)){
		$('#name1group').removeClass('has-success');
		$('#name1group').addClass('has-error');
		bl = false;
	}else{
		$('#name1group').removeClass('has-error');
		$('#name1group').addClass('has-success');
	}
	if(bl == false){
		$("#btnSave1").button('reset');
		util.errorMsg('资料填写不完整，请核对');
		return false;
	}
	var obj = {};
	obj.typename = name;
	return obj;
}
function saveData(){
	$("#btnSave1").button('loading');
	var util = new Util();
	var obj = bulidData();
	if(obj != false){
		var content = JSON.stringify(obj);
		var objdata = {};
		objdata.content = base64_encode(encodeURI(content));
		util.showLoading();
		var url = "/Admin/Admin/addProductSubject/";
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if (data=="1") {
						$("#btnSave1").button("reset");
						util.hideLoading();
						util.successMsg('保存作品分类成功');
						resetForm();
				}else{
						$("#btnSave1").button("reset");
						util.hideLoading();
						util.errorMsg('保存作品分类失败');
				}
			},
			objdata,
			function(XMLHttpRequest, textStatus, errorThrown) {
				util.errorMsg('内部服务器错误');
				util.hideLoading();
				$("#btnSave1").button("reset");
			}
		);
	}
}
$(document).ready(function() {
	$("#btnSave1").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});