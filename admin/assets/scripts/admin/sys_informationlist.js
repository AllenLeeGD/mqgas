//获取类型

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
                    "sAjaxSource": "../index.php/Admin/Admin/findInformation", // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]], // set first column as a default sort by asc
                    "fnServerParams" : function (aoData) {
					aoData.push(
						{ "name": "title_search", "value": $("#title_search").val() }
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
function openInformationDetail(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-view");
	util.showLoading();
	openmodal.load('sys_informationdetail.html', '', function() {
		util.getUrl("/Admin/Admin/findInformationByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objinfo = JSON.parse(data);
				var title = objinfo[0].title;
				var remark = objinfo[0].remark;
				var headicon = objinfo[0].imgpath;
				var status = objinfo[0].status;
				if(status==1){
					status = "是";
				}else{
					status = "否";
				}
				if(!util.isNullStr(headicon)){
					headicon = "<img src=\""+ edu_host + "/Upload/" + headicon +"\" style=\"height:200px;\">";
				}else{
					headicon = "";
				}
				var content = objinfo[0].content;
				var ivtime = objinfo[0].addtime;
				var sortno = objinfo[0].sortno;
				$('#title_idetail').html(title);
				$('#remark_idetail').html(remark);
				$('#headicon_idetail').html(headicon);
				$('#content_idetail').html(content);
				$('#status_idetail').html(status);
				$('#addtime_idetail').html(ivtime);
				$('#sortno_idetail').html(sortno);
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
function openInformationEdit(pkid,startindex,params) {
	window.location.href = "sys_informationedit.php?tag=sysadmin&item=16&start="+startindex+"&pkid="+pkid+"&params="+base64_encode(params);
}
function openInformationDel(pkid,index) {
	$("#view_data").data("delpkid", pkid);
	$("#view_data").data("delindex", index);
	$("#del_confirm").modal('show');
}
function doDel() {
	var pkid = $("#view_data").data("delpkid");
	var index = $("#view_data").data("delindex");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/delInformation/pkid/' + pkid, function(data, status) {
		if (data == "1") {
			util.successMsg('删除成功');
			$("#del_confirm").modal('hide');
			ProductAdd.init("",index);
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
	App.init();
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)){
		start = 0;
	}
	if(util.isNullStr(params)){
		ProductAdd.init("../index.php/Admin/Admin/findInformation",start);
	}else{
		params = base64_decode(params);
		var arrparam = params.split(',');
		var arrval0 = arrparam[0].split(':');
		$('#title_search').val(arrval0[1]);
		ProductAdd.init("../index.php/Admin/Admin/findInformation",start);
	}
	
});