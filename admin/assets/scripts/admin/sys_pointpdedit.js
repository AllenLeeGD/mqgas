function loadType(typeid) {
	var util = new Util();
	util.showLoading();
	util.getUrl("/Admin/Admin/findProductTypeSelect", function(data, status) {
		try {
			var list = JSON.parse(data);
            for(var i=0;i<list.length;i++){
                var key = list[i].pkid;
                var val = list[i].typename;
                var sel = '';
                if(typeid==key)
                {
                    sel = 'selected';
                }
                var tpl = "<option value=\"${key}\" "+sel+">${val}</option>";
                tpl = tpl.replace('\$\{key\}', key);
                tpl = tpl.replace('\$\{val\}', val);
                var old = $("#typeid").html();
                $("#typeid").html(old+tpl);
            }
		} catch (err) {
			util.errorMsg('加载分类时出错');
		} finally {
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
}
function resetForm(){
	var util = new Util();
	var start = util.getParam('start');
	var params = util.getParam('params');
	if(util.isNullStr(start)){
		start = 0;
	}
	window.location.href = "sys_pointpdlist.php?tag=sysadmin&item=0&start="+start+"&params="+params;
}
function bulidData(){
	var util = new Util();
	var pdname = $("#pdname").val().trim();
	var typeid = $("#typeid").val();
	var pdpicdata = $("#pdpicdata").val();
	var pdpic = $("#pdpic").val();
	for(var instanceName in CKEDITOR.instances) {
        CKEDITOR.instances[instanceName].updateElement();
    }
	var remarks = CKEDITOR.instances.remarks.getData();
	var generalprice = $("#generalprice").val().trim();
	var vipprice = $("#vipprice").val().trim();
	var standard = $("#standard").val().trim();
	var sortno = $("#sortno").val().trim();
	var bl = true;
	if(util.isNullStr(pdname)){
		$('#pdnamegroup').removeClass('has-success');
		$('#pdnamegroup').addClass('has-error');
		$('#pdnamesuc').hide();
		$('#pdnameerr').show();
		bl = false;
	}else{
		$('#pdnamegroup').removeClass('has-error');
		$('#pdnamegroup').addClass('has-success');
		$('#pdnameerr').hide();
		$('#pdnamesuc').show();
	}
	if(util.isNullStr(pdpic) && util.isNullStr(pdpicdata)){
		$('#pdpicgroup').removeClass('has-success');
		$('#pdpicgroup').addClass('has-error');
		bl = false;
	}else{
		$('#pdpicgroup').removeClass('has-error');
		$('#pdpicgroup').addClass('has-success');
	}
	if(util.isNullStr(remarks)){
		$('#remarksgroup').removeClass('has-success');
		$('#remarksgroup').addClass('has-error');
		bl = false;
	}else{
		$('#remarksgroup').removeClass('has-error');
		$('#remarksgroup').addClass('has-success');
	}
	if(util.isNullStr(generalprice) || !isNumber(generalprice)){
		$('#generalpricegroup').removeClass('has-success');
		$('#generalpricegroup').addClass('has-error');
		$('#generalpricesuc').hide();
		$('#generalpriceerr').show();
		bl = false;
	}else{
		$('#generalpricegroup').removeClass('has-error');
		$('#generalpricegroup').addClass('has-success');
		$('#generalpriceerr').hide();
		$('#generalpricesuc').show();
	}
	if(util.isNullStr(vipprice) || !isNumber(vipprice)){
		vipprice = "";
	}
	if(util.isNullStr(sortno) || !isInteger(sortno)){
		sortno = "0";
	}
	if(bl == false){
		$("#btnSave").button('reset');
		util.errorMsg('资料填写不完整，请核对');
		return false;
	}
	if(util.isNullStr(standard)){
		standard = "";
	}
	var updowntag = 0;
	if($('#updowntag').prop('checked') == true){
		updowntag = 1;
	}
	var obj = {};
	obj.pdname = pdname;
	obj.description = remarks;
	obj.updowntag = updowntag;
	obj.generalprice = generalprice;
	obj.vipprice = vipprice;
	obj.standard = standard;
	obj.sortno = sortno;
	obj.typeid = typeid;
	return obj;
}
function saveData(){
	$("#btnSave").button('loading');
	var obj = bulidData();
	var util = new Util();
	util.showLoading();
	var pkid = util.getParam('pkid');
	if(obj != false){
		var pdpicdata = $("#pdpicdata").val();
		var pdpic = $("#pdpic").val();
		if(!util.isNullStr(pdpic))
		{
			$.ajaxFileUpload({
				url: '../index.php/Admin/Admin/upload',
				type: 'post',
				secureuri: false, //一般设置为false
				fileElementId: 'pdpic', // 上传文件的id、name属性名
				dataType: 'text',
				success: function(data, status) {
					obj.pkid = pkid;
					obj.imgpath = data;
					var content = JSON.stringify(obj);
					var objdata = {};
					objdata.content = base64_encode(encodeURI(content));
					var url = "/Admin/Admin/saveProduct/";
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
		}else{
			obj.pkid = pkid;
			obj.imgpath = pdpicdata;
			var content = JSON.stringify(obj);
			var objdata = {};
			objdata.content = base64_encode(encodeURI(content));
			util.showLoading();
			var url = "/Admin/Admin/saveProduct/";
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
		}
		
	}
}
function loadData(pkid){
	var util = new Util();
	util.showLoading();
	var typeid = "";
	util.getUrl("/Admin/Admin/findProductByPkid/pkid/" + pkid, function(data, status) {
		try {
			var objdata = JSON.parse(data);
			var pdname = objdata[0].pdname;
			var remarks = objdata[0].description;
			var sortno = objdata[0].sortno;
			var pdpic = objdata[0].imgpath;
			var generalprice = objdata[0].generalprice;
			var vipprice = objdata[0].vipprice;
			var updowntag = objdata[0].updowntag;
			var standard = objdata[0].standard;
			typeid = objdata[0].typeid;
			var pdpicimg = "";
			if(!util.isNullStr(pdpic)){
				pdpicimg = "<img src=\""+ edu_host + "/Upload/" + pdpic +"\" style=\"height:26px;\">";
			}
			$('#pdname').val(pdname);
			$('#generalprice').val(generalprice);
			$('#vipprice').val(vipprice);
			$('#standard').val(standard);
			//CKEDITOR.instances.content.setData(content);
			//document.getElementById('content').innerHTML = "";
			/*if (CKEDITOR.instances['content']){        
				CKEDITOR.remove(CKEDITOR.instances['content']);    
			}   
			var editor = CKEDITOR.replace('content');*/
			document.getElementById('remarks').innerHTML = remarks;
			if (CKEDITOR.instances['remarks']){        
				CKEDITOR.remove(CKEDITOR.instances['remarks']);    
			}   
			var editor = CKEDITOR.replace('remarks');
			$('#sortno').val(sortno);
			$('#pdpicdata').val(pdpic);
			if(updowntag==1){
				$('#updowntag').prop('checked',true);
				$('#updowntag').parent('span').addClass('checked');
			}else if(updowntag==0){
				$('#updowntag').prop('checked',false);
				$('#updowntag').parent('span').removeClass('checked');
			}
			$('#pdpicimg').html("<a id=\"fbox_0\" class=\"fancybox-button\" data-rel=\"fancybox-button\" href=\""+edu_host + "/Upload/" + pdpic+"\">"+pdpicimg+"</a>&nbsp;&nbsp;上传商品图片文件（文件大小不超过2MB）");
		} catch (err) {
			util.errorMsg('找不到该记录');
		} finally {
			loadType(typeid);
			util.hideLoading();
		}
	}, function() {
		util.hideLoading();
		util.errorMsg('内部服务器错误');
	});
}
$(document).ready(function() {
	var util = new Util();
	var pkid = util.getParam('pkid');
	if(util.isNullStr(pkid)){
		util.errorMsg('缺少必要参数:pkid');
		return;
	}
	loadData(pkid);
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});