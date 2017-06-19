mui.ready(function() {
	mui.init();
	mui(".mui-scroll-wrapper").scroll();
	document.getElementById("btnSave").addEventListener("tap", function() {
		saveInfo();
	});
});