// 节点提示  
function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css({
		position: 'absolute',
		display: 'none',
		top: y + 10,
		left: x + 10,
		border: '1px solid #fdd',
		padding: '2px',
		'background-color': '#dfeffc',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}
//获取用户数量
function loadCountData() {
	var util = new Util();
	util.getUrl("/Admin/Count/findUsersCount", function(data, status) {
		try {
			$("#stu_count").html(data.stu);
			$("#org_count").html(data.org);
			$("#tutor_count").html(data.tutor);
			$("#school_count").html(data.school);
		} catch (err) {
			util.errorMsg('加载数据出错');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.errorMsg('内部服务器错误');
	});
}

function drawOrder(startdate, enddate) {
	var start = new Date(Date.parse(startdate)).getTime() / 1000;
	var end = (new Date(Date.parse(enddate)).getTime() / 1000) + 24 * 60 * 60;
	var util = new Util();
	util.getUrl("/Admin/Count/findOrderCount/startdate/" + start + "/enddate/" + end, function(data, status) {
		try {
			if (data) {
				var good = new Array();
				if (data.tutor && data.tutor.length > 0) {
					for (var i = 0; i < data.tutor.length; i++) {
						var item = new Array();
						var tutor = data.tutor[i];
						item.push(new Date(Date.parse(tutor.tutordate)).getTime());
						item.push(parseInt(tutor.tutororder));
						good.push(item);
					}
				}
				var middle = new Array();
				if (data.org && data.org.length > 0) {
					for (var i = 0; i < data.org.length; i++) {
						var item = new Array();
						var org = data.org[i];
						item.push(new Date(Date.parse(org.orgdate)).getTime());
						item.push(parseInt(org.orgorder));
						middle.push(item);
					}
				}
				var plot = $.plot($("#chart_comment"), [{
					data: good,
					label: "家教",
					lines: {
						lineWidth: 1,
					},
					shadowSize: 0

				}, {
					data: middle,
					label: "机构",
					lines: {
						lineWidth: 1,
					},
					shadowSize: 0
				}], {
					series: {
						lines: {
							show: true,
							lineWidth: 2,
							fill: true
						},
						points: {
							show: true,
							radius: 3,
							lineWidth: 1
						},
						shadowSize: 2
					},
					grid: {
						hoverable: true,
						clickable: true,
						tickColor: "#eee",
						borderColor: "#eee",
						borderWidth: 1
					},
					colors: ["#ff6600", "#37b7f3"],
					xaxis: {
						mode: "time",
						timeformat: "%m-%d",
						tickColor: "#eee",
					},
					yaxis: {
						show: true,
						tickDecimals: 0,
						tickColor: "#eee",
					}
				});
			}
		} catch (err) {
			util.errorMsg('加载数据出错');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.errorMsg('内部服务器错误');
	});
}
var previousPoint;
$("#chart_comment").bind("plothover", function(event, pos, item) {
	if (item) {
		if (previousPoint != item.dataIndex) {
			previousPoint = item.dataIndex;
			$("#tooltip").remove();
			var datetime = new Date(item.datapoint[0]);
			var x = (datetime.getMonth() + 1) + "-" + datetime.getDate();
			var y = item.datapoint[1];

			var tip = x + "成交量:" + y;
			showTooltip(item.pageX, item.pageY, tip);
		}
	} else {
		$("#tooltip").remove();
		previousPoint = null;
	}
});
$("#searchorder").bind("click", function() {
	var util = new Util();
	var startstr = $("#order_start").val();
	var endstr = $("#order_end").val();
	if (startstr == "") {
		util.errorMsg('请选择开始日期');
	}
	if (startstr == "") {
		util.errorMsg('请选择结束日期');
	}
	drawOrder(startstr, endstr);
});
//交易量
function init_order() {
	var now = new Date();
	var enddate = now.getFullYear() + "/" + ((now.getMonth() + 1) < 10 ? ("0" + (now.getMonth() + 1)) : (now.getMonth() + 1)) + "/" + (now.getDate() < 10 ? ("0" + now.getDate()) : now.getDate());
	$("#order_end").val(enddate);
	var before30 = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
	var startdate = before30.getFullYear() + "/" + ((before30.getMonth() + 1) < 10 ? ("0" + (before30.getMonth() + 1)) : (before30.getMonth() + 1)) + "/" + (before30.getDate() < 10 ? ("0" + before30.getDate()) : before30.getDate());
	$("#order_start").val(startdate);
	drawOrder(startdate, enddate);
}

function drawUsers(startdate, enddate) {
	var start = new Date(Date.parse(startdate)).getTime() / 1000;
	var end = (new Date(Date.parse(enddate)).getTime() / 1000) + 24 * 60 * 60;
	var util = new Util();
	util.getUrl("/Admin/Count/findUserCount/startdate/" + start + "/enddate/" + end, function(data, status) {
		try {
			var userdata = new Array();
			var daymap = {};
			if (data && data.length > 0) {
				for (var i = 0; i < data.length; i++) {
					var item = new Array();
					var tutor = data[i];
					if (daymap[tutor.userdate + "_" + tutor.mobile] == undefined || daymap[tutor.userdate + "_" + tutor.mobile]==null) {
						daymap[tutor.userdate + "_" + tutor.mobile] = tutor;
						item.push(new Date(Date.parse(tutor.userdate)).getTime());
						item.push(parseInt(tutor.usercount));
						userdata.push(item);
					}
				}
			}
			var plot = $.plot($("#chart_income"), [{
				data: userdata,
				label: "在线用户",
				lines: {
					lineWidth: 1,
				},
				shadowSize: 0

			}], {
				series: {
					lines: {
						show: true,
						lineWidth: 2,
						fill: true
					},
					points: {
						show: true,
						radius: 3,
						lineWidth: 1
					},
					shadowSize: 2
				},
				grid: {
					hoverable: true,
					clickable: true,
					tickColor: "#eee",
					borderColor: "#eee",
					borderWidth: 1
				},
				colors: ["#52e136"],
				xaxis: {
					mode: "time",
					timeformat: "%m-%d",
					tickColor: "#eee",
				},
				yaxis: {
					show: true,
					tickDecimals: 0,
					tickColor: "#eee",
				}
			});
		} catch (err) {
			util.errorMsg('加载数据出错');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.errorMsg('内部服务器错误');
	});
}
//在线用户情况
function init_users() {
	var now = new Date();
	var enddate = now.getFullYear() + "/" + ((now.getMonth() + 1) < 10 ? ("0" + (now.getMonth() + 1)) : (now.getMonth() + 1)) + "/" + (now.getDate() < 10 ? ("0" + now.getDate()) : now.getDate());
	$("#user_end").val(enddate);
	var before30 = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
	var startdate = before30.getFullYear() + "/" + ((before30.getMonth() + 1) < 10 ? ("0" + (before30.getMonth() + 1)) : (before30.getMonth() + 1)) + "/" + (before30.getDate() < 10 ? ("0" + before30.getDate()) : before30.getDate());
	$("#user_start").val(startdate);
	drawUsers(startdate, enddate);

}
var ppreviousPoint;
$("#chart_income").bind("plothover", function(event, pos, item) {
	if (item) {
		if (ppreviousPoint != item.dataIndex) {
			ppreviousPoint = item.dataIndex;
			$("#tooltip").remove();
			var datetime = new Date(item.datapoint[0]);
			var x = (datetime.getMonth() + 1) + "-" + datetime.getDate();
			var y = item.datapoint[1];

			var tip = x + "在线用户:" + y;
			showTooltip(item.pageX, item.pageY, tip);
		}
	} else {
		$("#tooltip").remove();
		ppreviousPoint = null;
	}
});
$("#searchuser").bind("click", function() {
	var util = new Util();
	var startstr = $("#user_start").val();
	var endstr = $("#user_end").val();
	if (startstr == "") {
		util.errorMsg('请选择开始日期');
	}
	if (startstr == "") {
		util.errorMsg('请选择结束日期');
	}
	drawUsers(startstr, endstr);
});
$(document).ready(function() {
	if (jQuery().datepicker) {
		$('.date-picker').datepicker({
			rtl: App.isRTL(),
			autoclose: true
		});
		$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	}
	loadCountData();
	init_order();
	init_users();
});