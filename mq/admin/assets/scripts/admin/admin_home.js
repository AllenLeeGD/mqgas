//获取数量
function loadCountData() {
	var util = new Util();
	util.getUrl("/Mq/Order/findUsersCount", function(data, status) {
		try {
			$("#member_count").html(data.members);
			$("#order_count").html(data.orders);
		} catch (err) {
			util.errorMsg('加载数据出错');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.errorMsg('内部服务器错误');
	});
}

$(document).ready(function() {
	loadCountData();
});