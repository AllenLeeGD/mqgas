function bulidData(){
	var util = new Util();
	var costvalue = $("#costvalue").val().trim();
	var countnumber = $("#countnumber").val().trim();
	var enddate = $("#enddate").val().trim();
	var type = $("#type").val().trim();
	
	if(util.isNullStr(costvalue) || util.isNullStr(countnumber) || util.isNullStr(enddate)){
		return false;
	}
	var obj = {};
	obj.costvalue = costvalue;
	obj.countnumber = countnumber;
	obj.enddate = enddate;
	obj.usergroup = type;
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
		var url = "/Mq/Coupon/addcoupon";
		util.postUrl(
			url,
			function(data, status) { //如果调用php成功  
				if (data=="yes") {
						$("#btnSave").button("reset");
						util.hideLoading();
						util.successMsg('生成优惠券成功');
						loadData();
				}else{
						$("#btnSave").button("reset");
						util.hideLoading();
						util.errorMsg('生成优惠券失败');
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
	util.getUrl("/Mq/Coupon/findCouponStatus", function(data, status) {
		try {
			var map = {};
			var util = new Util();
			for(var i = 0;i<data.length;i++){
				var _item = data[i];
				if(util.isNullStr(map[_item.usevalue])){
					map[_item.usevalue]={};
					map[_item.usevalue]['total'] = 0;
					map[_item.usevalue]['lingyong'] = 0;
					map[_item.usevalue]['used'] = 0;
				}
				map[_item.usevalue]['total'] += parseInt(_item.c);
				if(_item.status!=0){
					map[_item.usevalue]['lingyong'] += parseInt(_item.c);
				}
				if(_item.status==3){
					map[_item.usevalue]['used'] = parseInt(_item.c);
				}
				map[_item.usevalue]['levelname'] = _item.levelname;
			}
			var result = "";
			for(var _item in map){
				result += "<label class=\"control-label col-md-12\">"+map[_item]['levelname']+"组&nbsp;"+_item+"元优惠券共"+map[_item]['total']+"张，其中已领"+map[_item]['lingyong']+"张，已使用"+map[_item]['used']+"张</label>";				
			}
			document.getElementById("couponquery").innerHTML=result;
		} catch (err) {
			util.errorMsg('找不到记录');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
	
	util.getUrl("/Mq/Member/findSetting", function(s_data, s_status) {
		var result = "";
		for(var j = 0;j<s_data.length;j++){
			result += "<option value=\""+s_data[j].pkid+"\">"+s_data[j].levelname+"</option>";								
		}
		document.getElementById("type").innerHTML=result;
	});
}
$(document).ready(function() {
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
});