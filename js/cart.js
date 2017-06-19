/**
 * 加载购物车信息.
 */
function loadcart() {
	var util = new Util();
	var userid = util.getvalueincache("USER_ID");
	if (util.isNullStr(userid)) {
		document.getElementById("cartcount").innerHTML = 0;
		document.getElementById("cartsum").innerHTML = 0;
		return;
	}
	mui.ajax(edu_host + '/index.php/Product/Product/loadcartsimple/userid/' + userid, {
		type: 'post',
		success: function(data) {
			if (data) {
				document.getElementById("cartcount").innerHTML = data.count;
				document.getElementById("cartsum").innerHTML = data.sum;
			} else {
				document.getElementById("cartcount").innerHTML = 0;
				document.getElementById("cartsum").innerHTML = 0;
			}
		}
	});
	mui.ajax(edu_host + '/index.php/Product/Product/loadcart/userid/' + userid, {
		type: 'post',
		success: function(data) {
			if (data && data[0]) {
				mui.ajax('cart.txt', {
					success: function(templatetext) {
						var result = "";
						for (var i = 0; i < data.length; i++) {
							var item = data[i];
							var this_temp = templatetext;
							this_temp = this_temp.replace("\$\{cartid\}", item.pkid);
							this_temp = this_temp.replace("\$\{cartid\}", item.pkid);
							this_temp = this_temp.replace("\$\{cartid\}", item.pkid);
							this_temp = this_temp.replace("\$\{cartid\}", item.pkid);
							this_temp = this_temp.replace("\$\{pdname\}", item.pdname);
							this_temp = this_temp.replace("\$\{numbers\}", item.numbers);
							this_temp = this_temp.replace("\$\{price\}", item.price);
							this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/Upload/" + item.imgpath);
							result += this_temp;
						}
						document.getElementById("cartList").innerHTML = result;
						var eles = document.getElementsByName("des");
						for (var i = 0; i < eles.length; i++) {
							var item = eles[i];
							item.addEventListener("tap", function() {
								var cartid = this.getAttribute("cartid");
								var numbers = document.getElementById("numbers" + cartid).value;
								if (numbers > 0) {
									mui.ajax(edu_host + '/index.php/Product/Product/desCart/cartid/' + cartid, {
										type: 'post',
										success: function(data) {
											if (data == "yes") {
												loadcart();
											} else {
												mui.toast("操作失败");
											}
										}
									});
								}
							});
						}
						var eles = document.getElementsByName("plus");
						for (var i = 0; i < eles.length; i++) {
							var item = eles[i];
							item.addEventListener("tap", function() {
								var cartid = this.getAttribute("cartid");
								mui.ajax(edu_host + '/index.php/Product/Product/plusCart/cartid/' + cartid, {
									type: 'post',
									success: function(data) {
										if (data == "yes") {
											loadcart();
										} else {
											mui.toast("操作失败");
										}
									}
								});
							});
						}
					}
				});
			} else {
				document.getElementById("cartList").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}

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
document.getElementById("btnConfirm").addEventListener("tap",function(){
	var money = document.getElementById("cartsum").innerHTML;
	if(money==0){
		mui.toast("请先购买服务");
		return;
	}
	document.location.href = "confirmorders.html";
});
(function($) {
	$('#cartList').on('tap', '.btn-null', function(event) {
		var elem = this;
		//		var li = elem.parentNode.parentNode;
		var cartid = elem.getAttribute("cartid");
		mui.confirm('确定要删除该项目？', '删除', btnArray, function(e) {
			if (e.index == 0) {
				mui.ajax(edu_host + '/index.php/Product/Product/deleteproductfromcart/cartid/' + cartid, {
					type: 'post',
					success: function(data) {
						if (data == "yes") {
							//							li.parentNode.removeChild(li);
							loadcart();
						} else {
							mui.toast("删除失败");
						}
					}
				});

			} else {
				setTimeout(function() {
					$.swipeoutClose(li);
				}, 0);
			}
		});
	});
	var btnArray = ['确定', '取消'];
})(mui);