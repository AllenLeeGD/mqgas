/**
 * 加载产品信息. 
 * @param {Object} productid 产品ID.
 */
function loadProduct(productid) {
	var util = new Util();
	util.loadingmessage('加载中...');
	mui.ajax(edu_host + '/index.php/Product/Product/loadProducyById/productid/' + productid, {
		type: 'post',
		success: function(data) {
			if(data) {
				var usertype = util.getvalueincache("USER_TYPE");
				var template = document.getElementById('tpl_list').innerHTML;
				var pdname = data.pdname;
				var price = data.price;
				var pkid = data.pkid;
				var description = data.description;
				var standard = data.standard;
				var imgpath = data.imgpath;

				if(util.isNullStr(imgpath)) {
					imgpath = "<img style=\"width:100%;\" src=\"" + edu_host + "/images/nopic.jpg\">"
				} else {
					imgpath = "<img style=\"width:100%;\" src=\"" + edu_host + "/Upload/" + imgpath + "\">";
				}
				if(usertype == user_type_normal) {
					template = template.replace('\$\{price\}', data.generalprice + ' 元');
					document.getElementById("sum").innerHTML = data.generalprice + ' 元';
				} else if(usertype == user_type_vip) {
					template = template.replace('\$\{price\}', data.vipprice + ' 元');
					document.getElementById("sum").innerHTML = data.vipprice + ' 元';
				} else {
					template = template.replace('\$\{price\}', '-');
					document.getElementById("sum").innerHTML = '0 元';
				}
				template = template.replace('\$\{pdname\}', pdname);
				template = template.replace('\$\{standard\}', standard);
				template = template.replace('\$\{description\}', description);
				template = template.replace('\$\{imgpath\}', imgpath);
				document.getElementById('div_list').innerHTML = template;
				mui('.mui-numbox').numbox();
			} else {

			}
		},
		complete: function() {
			util.loadingEnd();
		}
	});
}
document.getElementById("btnDes").addEventListener("tap", function() {
	var price = parseFloat(document.getElementById("price").innerHTML);
	var numbers = parseInt(document.getElementById("txtNumbers").value);
	document.getElementById("sum").innerHTML = price * (numbers - 1);
});
document.getElementById("btnPlus").addEventListener("tap", function() {
	var price = parseFloat(document.getElementById("price").innerHTML);
	var numbers = parseInt(document.getElementById("txtNumbers").value);
	document.getElementById("sum").innerHTML = price * (numbers + 1);
});
document.getElementById("paytype").addEventListener("tap", function() {
	var userPicker = new mui.PopPicker();
	userPicker.setData([{
		value: 'wexin',
		text: '微信支付'
	}, {
		value: 'cash',
		text: '现金支付'
	}]);
	userPicker.show(function(items) {
		document.getElementById("paytype").innerHTML = '支付方式: ' +items[0].text;
		document.getElementById("paytype").value = items[0].value;
	});
});
document.getElementById("sendtime").addEventListener("tap", function() {
	var optionsJson = this.getAttribute('data-options') || '{}';
	var options = JSON.parse(optionsJson);
	var id = this.getAttribute('id');
	var picker = new mui.DtPicker(options);
	picker.show(function(rs) {
		document.getElementById(id).value = rs.text;
		document.getElementById(id).innerHTML = '送气时间: ' + rs.text;
		picker.dispose();
	});
});
document.getElementById("aAccount").addEventListener("tap",function(){
	document.location.href = "account.html";	
});
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	//	document.getElementById("aCart").addEventListener("tap", function(e) {
	//		document.location.href = "cart.html";
	//	});
	//	var util = new Util();
	//	var productid = util.getParam("productid");
	//	loadProduct(productid);

});