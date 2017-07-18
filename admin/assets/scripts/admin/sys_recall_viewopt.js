
var send_obj = {};
var send_vue;
function loadData() {
	var util = new Util();	
	send_obj.remark="";
	var pkid = util.getParam("pkid");
	util.postUrl(
		"/Mq/Check/loadrecall/pkid/"+pkid,
		function(data, status) { //如果调用php成功 
			send_obj = data;
			send_vue = new Vue({
				el:"#form_app",
				data:{sendobj:data}
			});		
		},
		function(XMLHttpRequest, textStatus, errorThrown) {
			
		}
	);
	
}

$(document).ready(function() {
	if(jQuery().datepicker) {
		$('.date-picker').datepicker({
			rtl: App.isRTL(),
			autoclose: true
		});
		$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	}
	loadData();
	$("#btnSave").bind('click', function() {
		document.location.href = "sys_recallopt.php?tag=sysadmin&item=14";
	});
	
});