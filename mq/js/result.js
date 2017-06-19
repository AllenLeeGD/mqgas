document.getElementById("aBack").addEventListener("tap",function(){
	document.location.href = "order.html";
});
mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	var util = new Util();
	var orderid = util.getParam("orderid");
	var money = util.getParam("money");
	if(util.isNullStr(orderid)){
		document.getElementById("resultstr").innerHTML = "付款失败";
	}else{
		document.getElementById("resultstr").innerHTML = "付款成功！&nbsp;订单号:"+orderid+"&nbsp;&nbsp;金额:"+money;
	}
});