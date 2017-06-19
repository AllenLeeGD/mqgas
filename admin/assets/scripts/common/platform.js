var eshop_url = "/Admin/Admin/checksession";
var util = new Util();
$.ajax({
type: 'POST',
url:edu_host+"/index.php/Admin/Admin/checksession",
success: function(data,status){
	  if(data=="pass"){
	  	
	  }else{
		  window.location.href = edu_host+"/admin/admin_login.html";
	  }
},
error: function(){  
      alert('服務連接錯誤，請聯繫管理員');  
}
});
