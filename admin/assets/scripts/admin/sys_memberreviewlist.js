var ProductAdd = function() {
	
	var handleOrder = function(url,startindex){
		if ($('#datatable_orders1').hasClass('dataTable')) {
			var dttable = $('#datatable_orders1').dataTable();
			dttable.fnClearTable(); //清空一下table
			dttable.fnDestroy(); //还原初始化了的datatable
		}
		
		var grid = new Datatable();
            grid.init({
                src: $("#datatable_orders1"),
                onSuccess: function(grid) {
                    // execute some code after table records loaded
                },
                onError: function(grid) {
                    // execute some code on network or other general error  
                },
                dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
                    "aLengthMenu": [
                        [20, 30],
                        [20, 30] // change per page values here
                    ],
                    "iDisplayStart": startindex,
                    "iDisplayLength": 20, // default record count per page
                    "bServerSide": true, // server side processing
                    "sAjaxSource": url, // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]], // set first column as a default sort by asc
                    "fnServerParams" : function (aoData) {
					aoData.push(
						{ "name": "realname_search", "value": $("#realname_search").val() },
						{ "name": "mobile_search", "value": $("#mobile_search").val() },
						{ "name": "idno_search", "value": $("#idno_search").val() },
						{ "name": "pkid_search", "value": $("#pkid_search").val()}
						);
					}
                }
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function(e){
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.addAjaxParam("sAction", "group_action");
                    grid.addAjaxParam("sGroupActionName", action.val());
                    var records = grid.getSelectedRows();
                    for (var i in records) {
                        grid.addAjaxParam(records[i]["name"], records[i]["value"]);    
                    }
                    grid.getDataTable().fnDraw();
                    grid.clearAjaxParams();
                } else if (action.val() == "") {
                    App.alert({type: 'danger', icon: 'warning', message: '请选择您要执行的操作', container: grid.getTableWrapper(), place: 'prepend'});
                } else if (action.val() != "Excel" && grid.getSelectedRowsCount() === 0) {
                    App.alert({type: 'danger', icon: 'warning', message: '请勾选要操作的记录', container: grid.getTableWrapper(), place: 'prepend'});
                } else{
                    
                }
            });
	};


	var initComponents = function() {
		//init datepickers
		/*$('.date-picker').datepicker({
		    rtl: App.isRTL(),
		    autoclose: true
		});

		//init datetimepickers
		$(".datetime-picker").datetimepicker({
		    isRTL: App.isRTL(),
		    autoclose: true,
		    todayBtn: true,
		    pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		    minuteStep: 10
		});*/

		//init maxlength handler
		/*$('.maxlength-handler').maxlength({
			limitReachedClass: "label label-danger",
			alwaysShow: true,
			threshold: 5
		});*/

		// slider 2
		/*$('#slider_2').noUiSlider({
		         range: [0,1000]
		        ,start: [10,30]
		        ,handles: 2
		        ,connect: true
		        ,step: 1
		        ,serialization: {
		             to: [$('#slider_2_input_start'), $('#slider_2_input_end')]
		            ,resolution: 1
		    }
		});*/
	};

	return {

		//main function to initiate the module
		init: function(url,startindex) {
			initComponents();
			//handleValidation1();
			handleOrder(url,startindex);
		}

	};

}();
function openMemberDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-view");
	util.showLoading();
	openmodal.load('sys_memberdetail.html', '', function() {
		util.getUrl("/Admin/Admin/findMemberByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var realname = objinfo[0].realname;
				var nickname = objinfo[0].nickname;
				var idno = objinfo[0].idno;
				var mobile = objinfo[0].mobile;
				var birthday = objinfo[0].birthday;
				var wechat = objinfo[0].wechat;
				var levelname = objinfo[0].levelname;
				var status = objinfo[0].status;
				var regtime = objinfo[0].regtime;
				var pointvalue = objinfo[0].pointvalue;
				var reviewtime = objinfo[0].reviewtime;
				var pdpic = objinfo[0].imgpath;
				if(!util.isNullStr(pdpic)){
					pdpic = "<img src=\""+ edu_host + "/Upload/" + pdpic +"\" style=\"height:100px;\">";
				}else{
					pdpic = "";
				}
				$('#realname_detail').html(realname);
				$('#nickname_detail').html(nickname);
				$('#idno_detail').html(idno);
				$('#mobile_detail').html(mobile);
				$('#birthday_detail').html(birthday);
				$('#wechat_detail').html(wechat);
				$('#levelname_detail').html(levelname);
				$('#status_detail').html(status);
				$('#regtime_detail').html(regtime);
				$('#pointvalue_detail').html(pointvalue);
				$('#reviewtime_detail').html(reviewtime);
				$('#pdpic_detail').html(pdpic);
			} catch (err) {
				util.errorMsg('找不到该记录');
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
function openMemberConfirm(pkid,index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#do_confirm").modal('show');
}
function doConfirm() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/reviewMember/pkid/' + pkid, function(data, status) {
		if (data == "1") {
			util.successMsg('审核成功');
			$("#do_confirm").modal('hide');
			ProductAdd.init("../index.php/Admin/Admin/findMemberReview",index);
		}
		util.hideLoading();
	},
	objdata,
	function(XMLHttpRequest, textStatus, errorThrown) {
		util.errorMsg('内部服务器错误');
		util.hideLoading();
	});
}
function openMemberReject(pkid,index) {
	$("#view_data").data("pkid", pkid);
	$("#view_data").data("index", index);
	$("#do_reject").modal('show');
}
function doReject() {
	var pkid = $("#view_data").data("pkid");
	var index = $("#view_data").data("index");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/rejectMember/pkid/' + pkid, function(data, status) {
		if (data == "1") {
			util.successMsg('拒绝成功');
			$("#do_reject").modal('hide');
			ProductAdd.init("../index.php/Admin/Admin/findMemberReview",index);
		}
		util.hideLoading();
	},
	objdata,
	function(XMLHttpRequest, textStatus, errorThrown) {
		util.errorMsg('内部服务器错误');
		util.hideLoading();
	});
}

$(document).ready(function() {
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)){
		start = 0;
	}
	if(util.isNullStr(params)){
		ProductAdd.init("../index.php/Admin/Admin/findMemberReview",start);
	}else{
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		var arrval1 = arrparam[1].split(':');
		var arrval2 = arrparam[2].split(':');
		var arrval3 = arrparam[3].split(':');
		$('#realname_search').val(arrval0[1]);
		$('#mobile_search').val(arrval1[1]);
		$('#idno_search').val(arrval2[1]);
		$('#pkid_search').val(arrval3[1]);
		ProductAdd.init("../index.php/Admin/Admin/findMemberReview",start);
	}
	
});