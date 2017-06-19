/**
 * 加载购物车信息.
 */
function loadcart() {
	var util = new Util();
	var userid = util.getvalueincache("USER_ID");
	if (util.isNullStr(userid)) {
		document.getElementById("price").innerHTML = 0;
		return;
	}
	mui.ajax(edu_host + '/index.php/Product/Product/loadcartsimple/userid/' + userid, {
		type: 'post',
		success: function(data) {
			if (data) {
				document.getElementById("price").innerHTML = data.sum;
			} else {
				document.getElementById("price").innerHTML = 0;
			}
		}
	});
	mui.ajax(edu_host + '/index.php/Product/Product/loadcart/userid/' + userid, {
		type: 'post',
		success: function(data) {
			if (data && data[0]) {
				mui.ajax('confirmorders.txt', {
					success: function(templatetext) {
						var result = "";
						for (var i = 0; i < data.length; i++) {
							var item = data[i];
							var this_temp = templatetext;
							this_temp = this_temp.replace("\$\{pdname\}", item.pdname);
							this_temp = this_temp.replace("\$\{numbers\}", item.numbers);
							this_temp = this_temp.replace("\$\{price\}", item.price);
							this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/Upload/" + item.imgpath);
							result += this_temp;
						}
						document.getElementById("cartList").innerHTML = result;												
					}
				});
			} else {
				document.getElementById("cartList").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}
document.getElementById("go_charge").addEventListener("tap",function(e){
	var util = new Util();
	util.putvalueincache("BACK_WALLET","confirmorders.html");
	document.location.href = "wallet.html";
});
document.getElementById("goto_pay_btn").addEventListener("tap",function(){
	var btnArray = ['确定', '取消'];
	var money = document.getElementById("price").innerHTML;
	if(money==0){
		mui.toast("请先购买服务");
		return;
	}
	var util = new Util();
	var userid = util.getvalueincache("USER_ID");
	mui.confirm('是否确定支付 '+money+' 元？', '付款', btnArray, function(e) {
			if (e.index == 0) {
				mui.ajax(edu_host + '/index.php/Order/Order/addCart2Order/userid/' + userid, {
					type: 'post',
					success: function(data) {
						if (data == "yes") {
							mui.toast("支付成功");
							setTimeout(function(){
								document.location.href = "service.html";
							},1000);
						}else if(data=='notenough'){
							mui.toast("余额不足，请先充值");
						} else {
							mui.toast("支付失败");
						}
					}
				});

			} else {
				
			}
		});
});
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	var slider = mui("#slider");
	slider.slider({
		interval: 4000
	});
	document.getElementById("aHome").addEventListener("tap", function(e) {
		document.location.href = "index.html";
	});
	loadcart();
});
