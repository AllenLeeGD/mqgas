function bulidData(){
	var util = new Util();
	var costvalue = $("#costvalue").val().trim();
	
	
	if(util.isNullStr(costvalue) ){
		return false;
	}
	var obj = {};
	obj.costvalue = costvalue;
	
	return obj;
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
		var url = "/Mq/Coupon/savePointSetting";
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if (data=="yes") {
						$("#btnSave").button("reset");
						util.hideLoading();
						util.successMsg('保存成功');
				}else{
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
}
function loadData(){
	var util = new Util();
	util.showLoading();
	util.getUrl("/Mq/Coupon/findPointSetting", function(data, status) {
		try {
			document.getElementById("costvalue").value = data;
			
		} catch (err) {
			util.errorMsg('找不到记录');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
}
$(document).ready(function() {
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
});