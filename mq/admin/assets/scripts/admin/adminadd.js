function resetForm() {
	$("#username").val('');
	$("#password").val('');
}

function saveData() {
	var util = new Util();
	$("#btnSave").button('loading');
	util.showLoading();
	var username = $("#username").val();
	var password = $("#password").val();
	var type = $("#type").val();
	var url = "/Admin/Order/addAdmin/username/"+username+"/password/"+password+"/type/"+type;
	util.postUrl(
		url,
		function(data, status) { //如果调用php成功  
			if (data == "yes") {
				$("#btnSave").button("reset");
				util.hideLoading();
				util.successMsg('生成成功');
				resetForm();
			} else {
				$("#btnSave").button("reset");
				util.hideLoading();
				util.errorMsg('生成失败');
			}
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			util.errorMsg('内部服务器错误');
			util.hideLoading();
			$("#btnSave").button("reset");
		}
	);

}
$(document).ready(function() {
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});