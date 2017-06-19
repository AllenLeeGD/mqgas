/**
Custom module for you to write your own javascript functions
**/
function getCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=")
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1
			c_end = document.cookie.indexOf(";", c_start)
			if (c_end == -1) c_end = document.cookie.length
			return unescape(document.cookie.substring(c_start, c_end))
		}
	}
	return ""
}

function checkbootstrapchk(obj) {
	if ($(obj).prop('checked') == true) {
		$(obj).parent('span').addClass('checked');
	} else {
		$(obj).parent('span').removeClass('checked');
	}
};

function checkbootstrapradio(obj) {
	var name = $(obj).attr('name');
	var alList = $("input[name='" + name + "']");
	alList.each(function() {
		$(this).parent('span').removeClass('checked');
		$(this).prop('checked', false);
	});
	$(obj).prop('checked', true);
	$(obj).parent('span').addClass('checked');
};

function getCityByCid(displayid, cityid) {
	$("#" + displayid).html("");
	var util = new Util();
	util.showLoading();
	if (cityid) {
		util.getUrl("/Provider/Syscity/getProvinceByPid/pid/" + cityid, function(data, status) {
			try {
				var list = JSON.parse(data);
				for (var i = 0; i < list.length; i++) {
					var key = list[i].class_id;
					var val = list[i].class_name;
					var tpl = "<option value=\"${key}\">${val}</option>";
					tpl = tpl.replace('\$\{key\}', key);
					tpl = tpl.replace('\$\{val\}', val);
					var old = $("#" + displayid).html();
					$("#" + displayid).html(old + tpl);
				}
			} catch (err) {

			} finally {
				util.hideLoading();
			}
		}, function() {
			util.hideLoading();
		});
	}
}

function getCityOrAreaByPid(parentid, displayid, parentidfromdb, selectedid) {
	var pid = $('#' + parentid).val();
	if (parentidfromdb != '') {
		pid = parentidfromdb;
	}
	$("#" + displayid).html("<option value=\"\">请选择</option>");
	var util = new Util();
	util.showLoading();
	if (pid) {
		util.getUrl("/Provider/Syscity/getCityOrAreaByPid/pid/" + pid, function(data, status) {
			try {
				var list = JSON.parse(data);
				for (var i = 0; i < list.length; i++) {
					var key = list[i].class_id;
					var val = list[i].class_name;
					var sel = '';
					if (selectedid == key) {
						sel = 'selected';
					}
					var tpl = "<option value=\"${key}\" " + sel + ">${val}</option>";
					tpl = tpl.replace('\$\{key\}', key);
					tpl = tpl.replace('\$\{val\}', val);
					var old = $("#" + displayid).html();
					$("#" + displayid).html(old + tpl);
				}
			} catch (err) {

			} finally {
				util.hideLoading();
			}
		}, function() {
			util.hideLoading();
		});
	}
}

function openProviderDetail(pid) {
	var util = new Util();
	var openmodal = $("#ajax-providerdetail");
	util.showLoading();
	openmodal.load('provider_detail.html', '', function() {
		util.getUrl("/Provider/Provider/getUserInfoByPid/pid/" + pid, function(data, status) {
			try {
				var providerinfo = JSON.parse(data);
				$('#pname_detail').html(providerinfo[0].pname);
				$('#citystr_detail').html(providerinfo[0].province + providerinfo[0].city + providerinfo[0].area);
				$('#address_detail').html(providerinfo[0].address);
				$('#contactperson_detail').html(providerinfo[0].contactperson);
				$('#telephone_detail').html(providerinfo[0].telephone);
				$('#mobilephone_detail').html(providerinfo[0].mobilephone);
				$('#email_detail').html(providerinfo[0].email);
			} catch (err) {
				util.errorMsg(err.message);
			} finally {
				util.hideLoading();
				openmodal.modal('show');
			}
		}, function() {
			util.hideLoading();
			util.errorMsg('内部服务器错误');
			openmodal.modal('hide');
		});
	});
}

function openAgentDetail(aid) {
	var util = new Util();
	util.showLoading();
	var openmodal = $("#ajax-agentdetail");
	openmodal.load('agent_detail.html', '', function() {
		util.getUrl("/Agent/Agent/getUserInfoByAid/aid/" + aid, function(data, status) {
			try {
				var agentinfo = JSON.parse(data);
				$('#aname_detail_a').html(agentinfo[0].aname);
				$('#citystr_detail_a').html(agentinfo[0].province + agentinfo[0].city + agentinfo[0].area);
				$('#address_detail_a').html(agentinfo[0].address);
				$('#contactperson_detail_a').html(agentinfo[0].contactperson);
				$('#telephone_detail_a').html(agentinfo[0].telephone);
				$('#mobilephone_detail_a').html(agentinfo[0].mobilephone);
				$('#email_detail_a').html(agentinfo[0].email);
			} catch (err) {
				util.errorMsg(err.message);
			} finally {
				util.hideLoading();
				openmodal.modal('show');
			}
		}, function() {
			hideLoading();
			util.errorMsg('内部服务器错误');
			openmodal.modal('hide');
		});
	});
}

function openPdDetail(pdid) {
	var util = new Util();
	util.showLoading();
	var openmodal = $("#ajax-pddetail");
	openmodal.load('provider_productdetail.html', '', function() {
		util.getUrl("/Agent/Agentbuy/getProviderProductDetailProvider/pdid/" + pdid, function(data, status) {
			if (data != "no") {
				try {
					var pdinfo = JSON.parse(data);
					$('#pdname_detail').html(pdinfo.pdname);
					var name = pdinfo.pdname;
					if (name.length > 40) {
						name = name.substring(0, 40) + "...";
					}
					$('#procnumber_detail').html(pdinfo.procnumber);
					$('#logo_detail').html(pdinfo.logo);
					$('#unit_detail').html(pdinfo.unit);
					$('#pdremark_detail').html(pdinfo.pdremark);
					var pdattrlist = eval(pdinfo.property);
					if (pdattrlist != null && pdattrlist.length > 0) {
						for (var i = 0; i < pdattrlist.length; i++) {
							var item = pdattrlist[i];
							var template = "<tr><td class=\"table-col-provider\">${attrkey}</td><td>${attrval}</td></tr>";
							var name = item.name;
							var value = item.value;
							template = template.replace('\$\{attrkey\}', name);
							template = template.replace('\$\{attrval\}', value);
							var old = $("#tpl_attr_content").html();
							$("#tpl_attr_content").html(old + template);
						}
					}
					var imglist = pdinfo.imglist;
					var folder = util.getPicFolder();
					if (imglist != null && imglist.length > 0) {
						for (var i = 0; i < imglist.length; i++) {
							var item = imglist[i];
							var template = "<img src=\"" + folder + "${imgpath}\" alt=\"\" /><br /><br />";
							var path = item.imgname;
							template = template.replace('${imgpath}', path);
							var old = $("#tpl_img_content").html();
							$("#tpl_img_content").html(old + template);
						}
					}
					var mainlist = pdinfo.standmainlist;
					var itemlist = pdinfo.standitemlist;
					if (mainlist != null && itemlist != null && mainlist.length > 0 && itemlist.length > 0) {
						for (var i = 0; i < mainlist.length; i++) {
							var mainid = mainlist[i].sid;
							var mainname = mainlist[i].name;
							var standstr = '';
							for (var a = 0; a < itemlist.length; a++) {
								var itemname = itemlist[a].standardvalue;
								var tempmainid = itemlist[a].sid;
								if (mainid == tempmainid) {
									standstr = standstr + itemname + ',';
								}
							}
							if (standstr != '') {
								standstr = standstr.substr(0, standstr.length - 1);
							}
							var template = "<tr><td class=\"table-col-provider\">${standkey}</td><td>${standval}</td></tr>";
							var name = item.name;
							var value = item.value;
							template = template.replace('\$\{standkey\}', mainname);
							template = template.replace('\$\{standval\}', standstr);
							var old = $("#tpl_stand_content").html();
							$("#tpl_stand_content").html(old + template);
						}
					}
				} catch (err) {
					util.errorMsg(err.message);
				} finally {
					util.hideLoading();
					openmodal.modal('show');
				}
			} else {
				util.hideLoading();
				util.errorMsg("找不到该商品");
				openmodal.modal('hide');
			}
		}, function() {
			util.hideLoading();
			util.errorMsg('内部服务器错误');
			openmodal.modal('hide');
		});
	});
}
function openAgentPdDetail(apdid) {
	var util = new Util();
	util.showLoading();
	var openmodal = $("#ajax-pddetail");
	openmodal.load('provider_productdetail.html', '', function() {
		util.getUrl("/Agent/Agentbuy/getAgentProductDetailProvider/apdid/" + apdid, function(data, status) {
			if (data != "no") {
				try {
					var pdinfo = JSON.parse(data);
					$('#pdname_detail').html(pdinfo.pdname);
					var name = pdinfo.pdname;
					if (name.length > 40) {
						name = name.substring(0, 40) + "...";
					}
					$('#procnumber_detail').html(pdinfo.procnumber);
					$('#logo_detail').html(pdinfo.logo);
					$('#unit_detail').html(pdinfo.unit);
					$('#pdremark_detail').html(pdinfo.pdremark);
					var pdattrlist = eval(pdinfo.property);
					if (pdattrlist != null && pdattrlist.length > 0) {
						for (var i = 0; i < pdattrlist.length; i++) {
							var item = pdattrlist[i];
							var template = "<tr><td class=\"table-col-provider\">${attrkey}</td><td>${attrval}</td></tr>";
							var name = item.name;
							var value = item.value;
							template = template.replace('\$\{attrkey\}', name);
							template = template.replace('\$\{attrval\}', value);
							var old = $("#tpl_attr_content").html();
							$("#tpl_attr_content").html(old + template);
						}
					}
					var imglist = pdinfo.imglist;
					var folder = util.getPicFolder();
					if (imglist != null && imglist.length > 0) {
						for (var i = 0; i < imglist.length; i++) {
							var item = imglist[i];
							var template = "<img src=\"" + folder + "${imgpath}\" alt=\"\" /><br /><br />";
							var path = item.imgname;
							template = template.replace('${imgpath}', path);
							var old = $("#tpl_img_content").html();
							$("#tpl_img_content").html(old + template);
						}
					}
					var mainlist = pdinfo.standmainlist;
					var itemlist = pdinfo.standitemlist;
					if (mainlist != null && itemlist != null && mainlist.length > 0 && itemlist.length > 0) {
						for (var i = 0; i < mainlist.length; i++) {
							var mainid = mainlist[i].sid;
							var mainname = mainlist[i].name;
							var standstr = '';
							for (var a = 0; a < itemlist.length; a++) {
								var itemname = itemlist[a].standardvalue;
								var tempmainid = itemlist[a].sid;
								if (mainid == tempmainid) {
									standstr = standstr + itemname + ',';
								}
							}
							if (standstr != '') {
								standstr = standstr.substr(0, standstr.length - 1);
							}
							var template = "<tr><td class=\"table-col-provider\">${standkey}</td><td>${standval}</td></tr>";
							var name = item.name;
							var value = item.value;
							template = template.replace('\$\{standkey\}', mainname);
							template = template.replace('\$\{standval\}', standstr);
							var old = $("#tpl_stand_content").html();
							$("#tpl_stand_content").html(old + template);
						}
					}
				} catch (err) {
					util.errorMsg(err.message);
				} finally {
					util.hideLoading();
					openmodal.modal('show');
				}
			} else {
				util.hideLoading();
				util.errorMsg("找不到该商品");
				openmodal.modal('hide');
			}
		}, function() {
			util.hideLoading();
			util.errorMsg('内部服务器错误');
			openmodal.modal('hide');
		});
	});
}
var Custom = function() {

	// private functions & variables

	var getUrlParam = function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");

		var r = window.location.search.substr(1).match(reg);

		if (r != null) return unescape(r[2]);

		return null;
	};

	var setMenu = function() {
		var tag = getUrlParam('tag');
		var item = getUrlParam('item');

		$('#' + tag).addClass("active");
		$('#' + tag + '_select').addClass("active");
		$('#' + tag + '_open').addClass("open");
		$('#' + tag + '_' + item).addClass("active");
	};

	//合并相同行的单元格
	//$('#table_id').mergeCell({    
	//            cols: arr //数组，如果要检查4列就要[0,1,2,3] 
	//    }); 
	$.fn.mergeCell = function(options) {
		return this.each(function() {
			var cols = options.cols;
			for (var i = cols.length - 1; cols[i] != undefined; i--) {
				// fixbug console调试   
				// console.debug(cols[i]);   
				mergeCell($(this), cols[i]);
			}
			dispose($(this));
		});
	};

	function mergeCell($table, colIndex) {
		$table.data('col-content', ''); // 存放单元格内容   
		$table.data('col-rowspan', 1); // 存放计算的rowspan值 默认为1   
		$table.data('col-td', $()); // 存放发现的第一个与前一行比较结果不同td(jQuery封装过的), 默认一个"空"的jquery对象   
		$table.data('trNum', $('tbody tr', $table).length); // 要处理表格的总行数, 用于最后一行做特殊处理时进行判断之用   
		// 对每一行数据进行"扫面"处理 关键是定位col-td, 和其对应的rowspan   
		$('tbody tr', $table).each(function(index) {
			// td:eq中的colIndex即列索引   
			var $td = $('td:eq(' + colIndex + ')', this);
			// 取出单元格的当前内容   
			var currentContent = $td.html();
			// 第一次时走此分支   
			if ($table.data('col-content') == '') {
				$table.data('col-content', currentContent);
				$table.data('col-td', $td);
			} else {
				// 上一行与当前行内容相同   
				if ($table.data('col-content') == currentContent) {
					// 上一行与当前行内容相同则col-rowspan累加, 保存新值   
					var rowspan = $table.data('col-rowspan') + 1;
					$table.data('col-rowspan', rowspan);
					// 值得注意的是 如果用了$td.remove()就会对其他列的处理造成影响   
					$td.hide();
					// 最后一行的情况比较特殊一点   
					// 比如最后2行 td中的内容是一样的, 那么到最后一行就应该把此时的col-td里保存的td设置rowspan   
					if (++index == $table.data('trNum'))
						$table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
				} else { // 上一行与当前行内容不同   
					// col-rowspan默认为1, 如果统计出的col-rowspan没有变化, 不处理   
					if ($table.data('col-rowspan') != 1) {
						$table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
					}
					// 保存第一次出现不同内容的td, 和其内容, 重置col-rowspan   
					$table.data('col-td', $td);
					$table.data('col-content', $td.html());
					$table.data('col-rowspan', 1);
				}
			}
		});
	}

	function dispose($table) {
		$table.removeData();
	}


	var myFunc = function(text) {
		alert(text);
	};

	// public functions
	return {

		//main function
		init: function() {
			//initialize here something.  
			setMenu();
		},

		//some helper function
		doSomeStuff: function() {
			myFunc();
		}

	};

}();

/***
Usage
***/
Custom.init();
//Custom.doSomeStuff();