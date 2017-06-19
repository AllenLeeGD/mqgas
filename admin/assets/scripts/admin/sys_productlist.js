

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
                    "sAjaxSource": "../index.php/Admin/Admin/findProductByReview", // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]], // set first column as a default sort by asc
                    "fnServerParams" : function (aoData) {
					aoData.push(
						{ "name": "name_search", "value": $("#name_search").val() },
						{ "name": "uname_search", "value": $("#uname_search").val() },
						{ "name": "status_search", "value": $("#status_search").val() }
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
function openProductReview(pkid,index) {
	var util = new Util();
	var openmodal = $("#ajax-productreview");
	$("#review_data").data("pkid", pkid);
	$("#review_data").data("index", index);
	util.showLoading();
	openmodal.load('sys_productdetail.html', '', function() {
		util.getUrl("/Admin/Admin/findProductByPkid/pkid/" + pkid, function(data, status) {
			try {
				var objdata = JSON.parse(data);
				var name = objdata[0].name;
				var uname = objdata[0].uname;
				var userid = objdata[0].userid;
				var remarks = objdata[0].remarks;
				var price = objdata[0].price;
				var countinventory = objdata[0].countinventory;
				var subjectstr = objdata[0].subjectstr;
				var cansale = objdata[0].cansale;
				var candeliver = objdata[0].candeliver;
				var canmail = objdata[0].canmail;
				var canface = objdata[0].canface;
				var logintype = objdata[0].logintype;
				var addtime = objdata[0].addtime;
				var imgstr = objdata[0].imgstr;
				var status = objdata[0].status;
				uname = uname + ' (ID:'+userid+')';
				price = '￥'+ price;
				var exchange = '';
				if(candeliver==1){
					exchange = '物流快递';
				}
				if(canmail==1){
					if(exchange!='')
						exchange += ',电邮传送';
				}
				if(canface==1){
					if(exchange!='')
						exchange += ',见面交易';
				}
				if(logintype=='student'){
					logintype = '学生';
				}else if(logintype=='tutor'){
					logintype = '老师';
				}else if(logintype=='org'){
					logintype = '机构';
				}
				var arrimg = imgstr.split(',');
				var pics = '';
				for(var aa=0;aa<arrimg.length;aa++)
				{
					if(!util.isNullStr(arrimg[aa])){
						pics += "<img src=\""+ edu_host + "/Upload/" + arrimg[aa] +"\" style=\"width:100%;\"><br />";
					}
				}
				var statusstr = '';
				if(status==1){
					$('#chk_id').prop('checked',true);
					statusstr = "<span style=\"color:#006600;\">已审核</span>";
				}else{
					$('#chk_id').prop('checked',false);
					statusstr = "<span style=\"color:#cc0000;\">未审核</span>";
				}
				$('#name_productdetail').html(name);
				$('#uname_productdetail').html(uname);
				$('#price_productdetail').html(price);
				$('#countinventory_productdetail').html(countinventory);
				$('#subjectstr_productdetail').html(subjectstr);
				$('#cansale_productdetail').html(cansale);
				$('#exchange_productdetail').html(exchange);
				$('#status_productdetail').html(statusstr);
				$('#addtime_productdetail').html(addtime);
				$('#imgstr_productdetail').html(pics);
			} catch (err) {
				util.errorMsg('找不到该作品信息');
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
	var index = $("#review_data").data("index");
	var chkid = 0;
	if($('#chk_id').prop('checked')==true){
		chkid = 1;
	}
	var obj = {};
	obj.pkid = pkid;
	obj.status = chkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/reviewproduct/pkid/' + pkid, function(data, status) {
		if (data == "1") {
			$("#ajax-productreview").modal('hide');
			ProductAdd.init("../index.php/Admin/Admin/findProductByReview",index);
		}
		util.hideLoading();
	},
	objdata,
	function(XMLHttpRequest, textStatus, errorThrown) {
		util.errorMsg('内部服务器错误');
		util.hideLoading();
	});

}
function openProductDel(pkid,index) {
	$("#review_data").data("delpkid", pkid);
	$("#review_data").data("delindex", index);
	$("#del_confirm").modal('show');
}
function doDel() {
	var pkid = $("#review_data").data("delpkid");
	var index = $("#review_data").data("delindex");
	var obj = {};
	obj.pkid = pkid;
	var content = JSON.stringify(obj);
	var objdata = {};
	objdata.content = base64_encode(encodeURI(content));
	var util = new Util();
	util.showLoading();
	util.postUrl('/Admin/Admin/delProduct/pkid/' + pkid, function(data, status) {
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
	ProductAdd.init("../index.php/Admin/Admin/findProductByReview",0);
});