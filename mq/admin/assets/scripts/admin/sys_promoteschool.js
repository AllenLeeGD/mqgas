

var ProductAdd = function() {
	
	var handleOrder = function(url){
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
                    "iDisplayLength": 20, // default record count per page
                    "bServerSide": true, // server side processing
                    "sAjaxSource": "../index.php/Admin/Admin/findSchoolByPromote", // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]], // set first column as a default sort by asc
                    "fnServerParams" : function (aoData) {
					aoData.push(
						{ "name": "schoolid_search", "value": $("#schoolid_search").val() },
						{ "name": "ispromote_search", "value": $("#ispromote_search").val() }
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
		init: function(url) {
			initComponents();
			//handleValidation1();
			handleOrder(url);
		}

	};

}();
function openSchoolPromote(pkid) {
	var util = new Util();
	var openmodal = $("#ajax-schoolreview");
	$("#review_data").data("pkid", pkid);
	util.showLoading();
	openmodal.load('sys_promoteschooldetail.html', '', function() {
		util.getUrl("/Admin/Admin/findSchoolPromoteByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objdata = JSON.parse(data);
				var sortno = objdata[0].sortno;
				var schoolname = objdata[0].schoolname;
				var schoolid = objdata[0].schoolid;
				var ispromote = objdata[0].ispromote;
				var status = objdata[0].status;
				var authzige = objdata[0].authzige;
				if(status==1){
					status = '已认证';
				}else{
					status = '未认证';
				}
				if(authzige==1){
					authzige = '已认证';
				}else{
					authzige = '未认证';
				}
				if(ispromote==1){
					$('#chk_id').prop('checked',true);
				}else{
					$('#chk_id').prop('checked',false);
				}
				$('#txt_sortno').val(sortno);
				$('#schoolname_spdetail').html(schoolname + '(ID:'+schoolid+')');
				$('#status_spdetail').html(status);
				$('#authzige_spdetail').html(authzige);
			} catch (err) {
				util.errorMsg('找不到学校信息');
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
function doReview() {
	var pkid = $("#review_data").data("pkid");
	var sortno = $("#txt_sortno").val();
	var chkid = 0;
	if($('#chk_id').prop('checked')==true){
		chkid = 1;
	}
	if(!sortno) 
		sortno = 0;
	var obj = {};
	obj.pkid = pkid;
	obj.ispromote = chkid;
	obj.sortno = sortno;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/reviewschoolpromote/lessonid/' + pkid, function(data, status) {
		if (data == "1") {
			$("#ajax-schoolreview").modal('hide');
			ProductAdd.init("../index.php/Admin/Admin/findSchoolByPromote");
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
	
});