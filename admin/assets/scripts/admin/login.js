$(document).ready(function(){
    App.init();
    Login.init();
    
    //点击回车登录
    $('.login-form input').keydown(function (e) {
        if (e.which == 13) {
            $("#login-submit-btn").click();
        }
    });
    
	//登录按钮
	$("#login-submit-btn").bind('click',function(){
        if ($('.login-form').validate().form()) {
//          $("#login-submit-btn").button('loading');
			var util = new Util();
			var url = "/Admin/Admin/login/username/"+$("#login_loginname").val()+"/password/"+$("#login_password").val();
			util.postUrl(url,function(data,status){//如果调用php成功   
					if(data=="yes"){
						document.location.href="admin_home.php?item=0";
					}else{
						util.errorMsg("密码错误");
//						 $("#login-submit-btn").button('reset');
					}
		         },function(){
                     util.errorMsg('内部服务器出错');
//                   $("#login-submit-btn").button('reset');
                 });			
        }			
	});
	
});
var Login = function () {

	var handleLogin = function() {

		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                login_loginname: {
	                    required: true
	                },
	                login_password: {
	                    required: true
	                }
	            },

	            messages: {
	                login_loginname: {
	                    required: "必须输入登录帐户."
	                },
	                login_password: {
	                    required: "必须输入登录密码."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit(); // form validation success, call ajax form submit
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    $('.login-form').submit(); //form validation success, call ajax form submit
	                }
	                return false;
	            }
	        });
	}

	var handleForgetPassword = function () {
		$('.forget-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                email: {
	                    required: true,
	                    email: true
	                }
	            },

	            messages: {
	                email: {
	                    required: "请输入您注册时使用的邮箱地址."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

	        $('.forget-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.forget-form').validate().form()) {
	                    $('.forget-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#forget-password').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.forget-form').show();
	        });

	        jQuery('#back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.forget-form').hide();
	        });

	        jQuery('#successback-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.success-form').hide();
	        });
			
	        jQuery('#success_submit').click(function () {
	            window.location.reload();
	        });
	}

	var handleRegister = function () {

		function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='assets/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        }


		$("#select2_sample4").select2({
		  	placeholder: '<i class="fa fa-map-marker"></i>&nbsp;Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


			$('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });


		
         $('.register-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                regemail: {
	                    required: true,
	                    email: true,
						remote:{
							url:"../native/agentlogin.php",
							type:"get",
							data:{
								type:function(){
									return "checkemail";
								},
								email:function(){
									return $("#regemail").val();
								}
							}
						}
	                },

	                loginname: {
	                    required: true,
						remote:{
							url:"../native/agentlogin.php",
							type:"get",
							data:{
								type:function(){
									return "checkname";
								},
								loginname:function(){
									return $("#loginname").val();
								}
							}
						}
	                },
	                password: {
	                    required: true
	                },
	                rpassword: {
	                    equalTo: "#password"
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                regemail:{
	                	remote:"邮箱已被注册，请重新输入"
	                },
	                loginname:{
	                	remote:"账户已被注册，请重新输入"
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
	                    error.insertAfter($('#register_tnc_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    $('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.register-form').hide();
	        });
	}
    
    return {
        //main function to initiate the module
        init: function () {
        	
            handleLogin();
            handleForgetPassword();
            handleRegister();        
	       
        }

    };

}();