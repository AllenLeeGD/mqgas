$(document).ready(function() {
	$("#change_provider_pwd").validate({
		errorElement : 'span', //default input error message container
		errorClass : 'help-block', // default input error message class
		focusInvalid : false, // do not focus the last invalid input
		ignore : "",
		rules : {
			password : {
				required : true
			},
			newpassword : {
				required : true
			},
			repeatpassword : {
				required : true,
				equalTo : "#newpassword"
			}
		},

		invalidHandler : function(event, validator) {//display error alert on form submit
			
		},

		highlight : function(element) {// hightlight error inputs
			$(element).closest('.form-group').addClass('has-error');
			// set error class to the control group
		},

		unhighlight : function(element) {// revert the change done by hightlight
			$(element).closest('.form-group').removeClass('has-error');
			// set error class to the control group
		},

		success : function(label) {
			label.closest('.form-group').removeClass('has-error');
			// set success class to the control group
		},

		submitHandler : function(form) {
			
		}
	});
	
	$("#save_change_pwd").bind('click',function(){
		if($("#change_provider_pwd").validate().form()){
			var util = new Util();
			util.getUrl("/Admin/Admin/changeAdminPwd/old/"+$("#password").val()+"/new/"+$("#newpassword").val(),function(data,status){
				if(data=='error'){
					util.errorMsg('原密码错误.');
				}else if(data=='yes'){
					util.successMsg('修改密码成功.');
					$('#change_pwd').modal('hide');
				}
			});
		}
	});
});
