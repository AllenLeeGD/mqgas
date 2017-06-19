
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
$(document).ready(function(){	
	
});
