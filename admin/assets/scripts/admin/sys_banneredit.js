function resetForm(){
	$('#bannergroup1').removeClass('has-success');
	$('#bannergroup1').removeClass('has-error');
	$('#bannergroup2').removeClass('has-success');
	$('#bannergroup2').removeClass('has-error');
	$('#bannergroup3').removeClass('has-success');
	$('#bannergroup3').removeClass('has-error');
	$('#bannergroup4').removeClass('has-success');
	$('#bannergroup4').removeClass('has-error');
	loadData();
}
function findSize(field_id){
	var fileInput = $("#"+field_id)[0];
	try{
		byteSize  = fileInput.files[0].size;
		return ( Math.ceil(byteSize / 1024) ); // Size returned in KB.
	}catch(e){
		return 0;
	}
}
function bulidData(){
	var util = new Util();
	var headicondata1 = $("#headicondata1").val();
	var headicon1 = $("#headicon1").val();
	var headicondata2 = $("#headicondata2").val();
	var headicon2 = $("#headicon2").val();
	var headicondata3 = $("#headicondata3").val();
	var headicon3 = $("#headicon3").val();
	var headicondata4 = $("#headicondata4").val();
	var headicon4 = $("#headicon4").val();
	var bl = true;
	if((util.isNullStr(headicon1) && util.isNullStr(headicondata1))||findSize('headicon1')>200){
		$('#bannergroup1').removeClass('has-success');
		$('#bannergroup1').addClass('has-error');
		bl = false;
	}else{
		$('#bannergroup1').removeClass('has-error');
		$('#bannergroup1').addClass('has-success');
	}
	if((util.isNullStr(headicon2) && util.isNullStr(headicondata2))||findSize('headicon2')>200){
		$('#bannergroup2').removeClass('has-success');
		$('#bannergroup2').addClass('has-error');
		bl = false;
	}else{
		$('#bannergroup2').removeClass('has-error');
		$('#bannergroup2').addClass('has-success');
	}
	if((util.isNullStr(headicon3) && util.isNullStr(headicondata3))||findSize('headicon3')>200){
		$('#bannergroup3').removeClass('has-success');
		$('#bannergroup3').addClass('has-error');
		bl = false;
	}else{
		$('#bannergroup3').removeClass('has-error');
		$('#bannergroup3').addClass('has-success');
	}
	if((util.isNullStr(headicon4) && util.isNullStr(headicondata4))||findSize('headicon4')>200){
		$('#bannergroup4').removeClass('has-success');
		$('#bannergroup4').addClass('has-error');
		bl = false;
	}else{
		$('#bannergroup4').removeClass('has-error');
		$('#bannergroup4').addClass('has-success');
	}
	if(bl == false){
		$("#btnSave").button('reset');
		util.errorMsg('横幅不符合要求，请核对图片大小');
		return false;
	}
	var obj = {};
	return obj;
}
function saveData(){
	$("#btnSave").button('loading');
	var obj = bulidData();
	var util = new Util();
	util.showLoading();
	if(obj != false){
		var headicondata1 = $("#headicondata1").val();
		var headicon1 = $("#headicon1").val();
		$.ajaxFileUpload({
			url: '../index.php/Admin/Admin/upload',
			type: 'post',
			secureuri: false, //一般设置为false
			fileElementId: 'headicon1', // 上传文件的id、name属性名
			dataType: 'text',
			success: function(data, status) {
				if(data.substr(0,5)=="Error"){
					data = headicondata1;
				}
				obj.headicon1 = data;
				var headicondata2 = $("#headicondata2").val();
				var headicon2 = $("#headicon2").val();
				$.ajaxFileUpload({
					url: '../index.php/Admin/Admin/upload',
					type: 'post',
					secureuri: false, //一般设置为false
					fileElementId: 'headicon2', // 上传文件的id、name属性名
					dataType: 'text',
					success: function(data, status) {
						if(data.substr(0,5)=="Error"){
							data = headicondata2;
						}
						obj.headicon2 = data;
						var headicondata3 = $("#headicondata3").val();
						var headicon3 = $("#headicon3").val();
							$.ajaxFileUpload({
								url: '../index.php/Admin/Admin/upload',
								type: 'post',
								secureuri: false, //一般设置为false
								fileElementId: 'headicon3', // 上传文件的id、name属性名
								dataType: 'text',
								success: function(data, status) {
									if(data.substr(0,5)=="Error"){
										data = headicondata3;
									}
									obj.headicon3 = data;
									var headicondata4 = $("#headicondata4").val();
									var headicon4 = $("#headicon4").val();
										$.ajaxFileUpload({
											url: '../index.php/Admin/Admin/upload',
											type: 'post',
											secureuri: false, //一般设置为false
											fileElementId: 'headicon4', // 上传文件的id、name属性名
											dataType: 'text',
											success: function(data, status) {
												if(data.substr(0,5)=="Error"){
													data = headicondata4;
												}
												obj.headicon4 = data;
												var content = JSON.stringify(obj);
												var objdata = {};
												objdata.content = base64_encode(encodeURI(content));
												var url = "/Admin/Admin/saveBanner/pkid/1";
												util.postUrl(
													url,
													function(data, status) { //如果调用php成功  
														if (data=="1") {
																$("#btnSave").button("reset");
																util.hideLoading();
																util.successMsg('保存成功');
																resetForm();
														}else{
																$("#btnSave").button("reset");
																util.hideLoading();
																util.errorMsg('保存失败');
														}
													},
													objdata,
													function(XMLHttpRequest, textStatus, errorThrown) {
														util.errorMsg('内部服务器错误');
														util.hideLoading();
														$("#btnSave").button("reset");
													}
												);
											},
											error: function(data, status, e) {
												util.errorMsg(e);
												$("#btnSave").button("reset");
											}
										});
								},
								error: function(data, status, e) {
									util.errorMsg(e);
									$("#btnSave").button("reset");
								}
							});
					},
					error: function(data, status, e) {
						util.errorMsg(e);
						$("#btnSave").button("reset");
					}
				});
			},
			error: function(data, status, e) {
				util.errorMsg(e);
				$("#btnSave").button("reset");
			},
		});
		
	}else{
		util.hideLoading();
		$("#btnSave").button("reset");
	}
}
function loadData(){
	var util = new Util();
	util.showLoading();
	var channelid = "";
	util.getUrl("/Admin/Admin/findBannerByPkid/pkid/1", function(data, status) {
		try {
			var objdata = JSON.parse(data);
			var headicon1 = objdata[0].headicon1;
			var headicon2 = objdata[0].headicon2;
			var headicon3 = objdata[0].headicon3;
			var headicon4 = objdata[0].headicon4;
			var headiconimg1 = "";
			if(!util.isNullStr(headicon1)){
				headiconimg1 = "<img src=\""+ edu_host + "/Upload/" + headicon1 +"\" style=\"height:50px;\">";
			}
			$('#headicondata1').val(headicon1);
			$('#headiconimg1').html("<a id=\"fbox_0\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + headicon1+"\">"+headiconimg1+"</a>&nbsp;&nbsp;上传横幅图片文件（文件大小不超过200kb）");
			var headiconimg2 = "";
			if(!util.isNullStr(headicon2)){
				headiconimg2 = "<img src=\""+ edu_host + "/Upload/" + headicon2+"\" style=\"height:50px;\">";
			}
			$('#headicondata2').val(headicon2);
			$('#headiconimg2').html("<a id=\"fbox_1\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + headicon2+"\">"+headiconimg2+"</a>&nbsp;&nbsp;上传横幅图片文件（文件大小不超过200kb）");
			var headiconimg3 = "";
			if(!util.isNullStr(headicon3)){
				headiconimg3 = "<img src=\""+ edu_host + "/Upload/" + headicon3+"\" style=\"height:50px;\">";
			}
			$('#headicondata3').val(headicon3);
			$('#headiconimg3').html("<a id=\"fbox_2\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + headicon3+"\">"+headiconimg3+"</a>&nbsp;&nbsp;上传横幅图片文件（文件大小不超过200kb）");
			var headiconimg4 = "";
			if(!util.isNullStr(headicon4)){
				headiconimg4 = "<img src=\""+ edu_host + "/Upload/" + headicon4+"\" style=\"height:50px;\">";
			}
			$('#headicondata4').val(headicon4);
			$('#headiconimg4').html("<a id=\"fbox_3\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + headicon4+"\">"+headiconimg4+"</a>&nbsp;&nbsp;上传横幅图片文件（文件大小不超过200kb）");
		} catch (err) {
			util.errorMsg('找不到该记录');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
}
$(document).ready(function() {
	var util = new Util();
	loadData();
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});