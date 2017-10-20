document.getElementById("paytype").addEventListener("tap", function() {
	var userPicker = new mui.PopPicker();
	userPicker.setData([{
		value: 0,
		text: '微信支付'
	}, {
		value: 1,
		text: '现金支付'
	}]);
	userPicker.show(function(items) {
		document.getElementById("paytype").innerHTML = '支付方式: ' + items[0].text;
		document.getElementById("paytype").value = items[0].value;
	});
});
document.getElementById("btnCoupon").addEventListener("tap",function(){
	var numbers = document.getElementById("txtNumbers").value;
	document.location.href="couponchoose.html?nu="+numbers;
});
//document.getElementById("sendtime").addEventListener("tap", function() {
//	var optionsJson = this.getAttribute('data-options') || '{}';
//	var options = JSON.parse(optionsJson);
//	var id = this.getAttribute('id');
//	var picker = new mui.DtPicker(options);
//	picker.show(function(rs) {
//		document.getElementById(id).value = rs.text;
//		document.getElementById(id).innerHTML = '送气时间: ' + rs.text;
//		picker.dispose();
//	});
//});
document.getElementById("aAccount").addEventListener("tap", function() {
	document.location.href = "account.html";
});

function checknull() {
	var name = document.getElementById("realname").value;
	var mobile = document.getElementById("mobile").value;
	var address = document.getElementById("address").value;
	var paytype = document.getElementById("paytype").value;
	var money = document.getElementById("price").innerHTML;
//	var sendtime = document.getElementById("sendtime").value;
	var util = new Util();
	if(util.isNullStr(name)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写联系人");
		return false;
	}
	if(util.isNullStr(mobile)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写联系电话");
		return false;
	}
	if(util.isNullStr(address)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请填写送货地址");
		return false;
	}
	if(util.isNullStr(paytype)) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请选择支付方式");
		return false;
	}
	if(parseFloat(money)<10) {
		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />支付金额错误");
		return false;
	}
//	if(util.isNullStr(sendtime)) {
//		mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />请选择送货时间");
//		return false;
//	}
	return true;
}
function loadinfo(){
	var util = new Util();
	var userid = util.getvalueincache("USERID");
	if(userid==null || userid=="" || userid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
	mui.ajax(edu_host + '/index.php/Mq/Member/loadUserinfo/pkid/'+userid, {
			type: 'post',
			success: function(data) {
				document.getElementById("realname").value = data.realname;
				document.getElementById("mobile").value = data.mobile;
				document.getElementById("address").value = data.address;
				document.getElementById("price").innerHTML = data.price;
			}
		});
}
document.getElementById("btnSave").addEventListener("tap", function() {	
	var util = new Util();
	
	if(checknull()) {
		var obj = {};
		obj.userid = util.getvalueincache("USERID");
		document.getElementById("btnSave").disabled=true;
		obj.realname = document.getElementById("realname").value;
		obj.mobile = document.getElementById("mobile").value;
		obj.address = document.getElementById("address").value;
		obj.remark = document.getElementById("remark").value;
		obj.paytype = document.getElementById("paytype").value;
//		obj.sendtime = document.getElementById("sendtime").value;
		obj.price = document.getElementById("price").innerHTML;
		obj.buycount = document.getElementById("txtNumbers").value;
		var testCoupon = util.getvalueincache("usecoupon");
		obj.couponnumbers = "";
		if(!util.isNullStr(testCoupon)){
			var couponobj = JSON.parse(util.getvalueincache("usecoupon"));
			obj.coupon = couponobj.money;
			for(var j=0;j<couponobj.list.length;j++){
				if(j==0){
					obj.couponnumbers=couponobj.list[j];
				}else{
					obj.couponnumbers=obj.couponnumbers+","+couponobj.list[j];	
				}			
			}
			if(couponobj.numbers>obj.buycount){
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />您最多只能使用"+obj.buycount+"张优惠券，请重新选择");
				return;
			}
			if(couponobj.numbers>0 && obj.paytype != "0"){
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />只有微信支付才可以使用优惠券");
				return;
			}
		}else{
			obj.coupon = 0;
		}
		
		mui.ajax(edu_host + '/index.php/Mq/Member/addOrder', {
			type: 'post',
			data: obj,
			success: function(data) {
				if(data.indexOf("yes") != -1){
//					if(obj.paytype=="0"){
//						var util = new Util();
//						var params = {};
//						params.orderid = data.substring(4,data.length);
//						params.openid = util.getvalueincache("OPENID");
//						params.money = ((obj.price*obj.buycount)-obj.coupon)+"";
//			
//						var temp = document.getElementById("dynamicForm");
//						temp.setAttribute("method", 'post');
//						temp.setAttribute("action", "/Payment/WX/gotopay.php");
//						temp.innerHTML = "";
//						var opt = document.createElement("input");
//						opt.setAttribute("type", "hidden");
//						opt.setAttribute("name", "payorderid");
//						opt.setAttribute("value", params.orderid);
//						temp.appendChild(opt);
//						var opt2 = document.createElement("input");
//						opt2.setAttribute("type", "hidden");
//						opt2.setAttribute("name", "paymoney");
//						opt2.setAttribute("value", params.money);
//						temp.appendChild(opt2);
//						var opt3 = document.createElement("input");
//						opt3.setAttribute("type", "hidden");
//						opt3.setAttribute("name", "payopenid");
//						opt3.setAttribute("value", params.openid);
//						temp.appendChild(opt3);
//			
//						temp.submit();
//					}else{
						if(obj.paytype=="0"){
//							mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />预定成功,请使用微信支付");
							var totalmoney = parseFloat(obj.price)*parseFloat(obj.buycount);
							var openid = util.getvalueincache("OPENID");
							var payorderid= data.substring(4,data.length);
							document.location.href="/Payment/WX/gotopay.php?payorderid="+payorderid+"&paymoney="+totalmoney+"&payopenid="+openid;
						}else{
							mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />预定成功,您的煤气将会尽快送达");	
						}						
						setTimeout(function(){
							document.location.href = "order.html";
						},1000);
//					}
				}else{
					mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />预定出错");
				}
			}
		});
	}
});
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	var util = new Util();
	var userid = util.getParam("userid");
	if(!util.isNullStr(userid)){
		
		util.putvalueincache("USERID",userid);
		var openid = util.getParam("openid");
		util.putvalueincache("OPENID",openid);
	}
	loadinfo();
	var usecoupon = util.getParam("usecoupon");
	if("yes"==usecoupon){
		var couponobj = JSON.parse(util.getvalueincache("usecoupon"));
		document.getElementById("txtNumbers").value=couponobj.numbers;
		document.getElementById("btnCoupon").innerHTML = "优惠金额:￥"+couponobj.money;
	}
});