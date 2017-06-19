function openAgent(aid){
	var util = new Util();
    var openmodal = $("#ajax-modal");
    util.showLoading();
    openmodal.load('admin_provider_certinfo.html',function(){
    		util.postUrl('/Admin/Admin/getAgentInfo/aid/'+aid,function(data,status){
    			var result = eval(data);
    			if(result){
    				var item = result[0];
    				$("#companyname").html(item.aname);
    				$("#domicile").html(item.domicile);
    				$("#address").html(item.address);
    				$("#code").html(item.code);
    				$("#annualsales").html(item.annualsales);
    				$("#employees").html(item.employees);
    				$("#mainproduct").html(item.mainproduct);
    				$("#sitearea").html(item.sitearea);
    				$("#margin").html(item.margin);
    				if(item.charter){
    					$("#charter_img").attr("src","../Upload/"+item.charter.replace("$","/"));
    				}
    				if(item.taxreg){
    					$("#taxreg_img").attr("src","../Upload/"+item.taxreg.replace("$","/"));
    				}
    				if(item.photowithshop){
    					$("#photowithshop_img").attr("src","../Upload/"+item.photowithshop.replace("$","/"));
    				}
    				$("#description").html(item.description);
    			}
    			openmodal.modal('show'); 
    			util.hideLoading();	
    		});
    		
    });
}
var ProviderOrder = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    
    

    var handleOrders = function(url) {
    	
    	if ($('#datatable_orders').hasClass('dataTable')) {
	        var dttable = $('#datatable_orders').dataTable();
	        dttable.fnClearTable(); //清空一下table
	        dttable.fnDestroy(); //还原初始化了的datatable
	   }


        var grid = new Datatable();
            grid.init({
                src: $("#datatable_orders"),
                onSuccess: function(grid) {
                    
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
                    "sAjaxSource": url, // ajax source
                    "bSort":false,//不允许排序
                    "aaSorting": [[ 1, "asc" ]] // set first column as a default sort by asc

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

    }

    return {

        //main function to initiate the module
        init: function (url) {
			
            initPickers();
            handleOrders(url);

        }

    };

}();


// $(".examine_value").change(function(){

//status/0

// });



function verify(aid,pname){
	$("#v_pname").html(pname);
	$("#agent_order_data").data("aid",aid);
	$("#send_confirm").modal('show');
}
$(document).ready(function(){	
	$("#confirm_send_btn").bind("click",function(){
		var aid = $("#agent_order_data").data("aid");
		util.postUrl('/Admin/Admin/authAgent/aid/'+aid+"/type/2",function(data,status){
			var util = new Util();
			if(data=="yes"){
				ProviderOrder.init("../index.php/Admin/Admin/getAgent/type/2/");
				util.successMsg('审核成功');
			}else{
				util.errorMsg('审核失败');
			}
			$("#send_confirm").modal('hide');
		});
	});
});
