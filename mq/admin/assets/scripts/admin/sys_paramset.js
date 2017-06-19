function bulidData(){
	var util = new Util();
	var l1name = $("#l1name").val().trim();
	var l1price = $("#l1price").val().trim();
	var l2name = $("#l2name").val().trim();
	var l2price = $("#l2price").val().trim();
	var l3name = $("#l3name").val().trim();
	var l3price = $("#l3price").val().trim();
	var l4name = $("#l4name").val().trim();
	var l4price = $("#l4price").val().trim();
	
	var l5name = $("#l5name").val().trim();
	var l5price = $("#l5price").val().trim();
	var l6name = $("#l6name").val().trim();
	var l6price = $("#l6price").val().trim();
	var l7name = $("#l7name").val().trim();
	var l7price = $("#l7price").val().trim();
	var l8name = $("#l8name").val().trim();
	var l8price = $("#l8price").val().trim();
	var l9name = $("#l9name").val().trim();
	var l9price = $("#l9price").val().trim();
	var l10name = $("#l10name").val().trim();
	var l10price = $("#l10price").val().trim();
	
	if(util.isNullStr(l1name) || util.isNullStr(l1price) || util.isNullStr(l2name) || util.isNullStr(l2price) || util.isNullStr(l3name) || util.isNullStr(l3price) || util.isNullStr(l4name) || util.isNullStr(l4price) ){
		return false;
	}
	var obj = {};
	obj.l1name = l1name;
	obj.l1price = l1price;
	obj.l2name = l2name;
	obj.l2price = l2price;
	obj.l3name = l3name;
	obj.l3price = l3price;
	obj.l4name = l4name;
	obj.l4price = l4price;
	
	obj.l5name = l5name;
	obj.l5price = l5price;
	obj.l6name = l6name;
	obj.l6price = l6price;
	obj.l7name = l7name;
	obj.l7price = l7price;
	obj.l8name = l8name;
	obj.l8price = l8price;
	obj.l9name = l9name;
	obj.l9price = l9price;
	obj.l10name = l10name;
	obj.l10price = l10price;
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
		var url = "/Mq/Member/saveUserSetting";
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
	util.getUrl("/Mq/Member/findSetting", function(data, status) {
		try {
			document.getElementById("l1name").value = data[0].levelname;
			document.getElementById("l1price").value = data[0].price;
			document.getElementById("l2name").value = data[1].levelname;
			document.getElementById("l2price").value = data[1].price;
			document.getElementById("l3name").value = data[2].levelname;
			document.getElementById("l3price").value = data[2].price;
			document.getElementById("l4name").value = data[3].levelname;
			document.getElementById("l4price").value = data[3].price;
			
			document.getElementById("l5name").value = data[4].levelname;
			document.getElementById("l5price").value = data[4].price;
			document.getElementById("l6name").value = data[5].levelname;
			document.getElementById("l6price").value = data[5].price;
			document.getElementById("l7name").value = data[6].levelname;
			document.getElementById("l7price").value = data[6].price;
			document.getElementById("l8name").value = data[7].levelname;
			document.getElementById("l8price").value = data[7].price;
			document.getElementById("l9name").value = data[8].levelname;
			document.getElementById("l9price").value = data[8].price;
			document.getElementById("l10name").value = data[9].levelname;
			document.getElementById("l10price").value = data[9].price;
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