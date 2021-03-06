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
	$("#pdname").val('');
	$("#pdpic").val('');
	$("#typeid").val('');
	CKEDITOR.instances.remarks.setData('');
	$("#generalprice").val('');
	$("#vipprice").val('');
	$("#sortno").val('');
	$("#standard").val('');
	$('#updowntag').prop('checked',true);
	$('#updowntag').parent('span').addClass('checked');
	$('#pdnamegroup').removeClass('has-success');
	$('#pdnamegroup').removeClass('has-error');
	$('#pdnamegroup').removeClass('has-success');
	$('#pdnamegroup').removeClass('has-error');
	$('#pdnamesuc').hide();
	$('#pdnameerr').hide();
	$('#generalpricegroup').removeClass('has-success');
	$('#generalpricegroup').removeClass('has-error');
	$('#vippricegroup').removeClass('has-success');
	$('#vippricegroup').removeClass('has-error');
	$('#generalpricesuc').hide();
	$('#generalpriceerr').hide();
	$('#vippricesuc').hide();
	$('#vippriceerr').hide();
	$('#remarksgroup').removeClass('has-success');
	$('#remarksgroup').removeClass('has-error');
	$('#pdpicgroup').removeClass('has-success');
	$('#pdpicgroup').removeClass('has-error');
	$("#updowntag").prop('checked',false);
	$("#updowntag").parent('span').removeClass('checked');
}
function bulidData(){
	var util = new Util();
	var pdname = $("#pdname").val().trim();
	var typeid = $("#typeid").val();
	var pdpicdata = $("#pdpicdata").val();
	var pdpic = $("#pdpic").val();
	var standard = $("#standard").val().trim();
	for(var instanceName in CKEDITOR.instances) {
        CKEDITOR.instances[instanceName].updateElement();
    }
	var remarks = CKEDITOR.instances.remarks.getData();
	var generalprice = $("#generalprice").val().trim();
	var vipprice = $("#vipprice").val().trim();
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
	if(util.isNullStr(standard)){
		standard = "";
	}
	if(bl == false){
		$("#btnSave").button('reset');
		util.errorMsg('资料填写不完整，请核对');
		return false;
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
	var util = new Util();
	var obj = bulidData();
	if(obj != false){
		$.ajaxFileUpload({
			url: '../index.php/Admin/Admin/upload',
			type: 'post',
			secureuri: false, //一般设置为false
			fileElementId: 'pdpic', // 上传文件的id、name属性名
			dataType: 'text',
			success: function(data, status) {
				obj.imgpath = data;
				var content = JSON.stringify(obj);
				var objdata = {};
				objdata.content = base64_encode(encodeURI(content));
				util.showLoading();
				var url = "/Admin/Admin/addProduct/";
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
		
	}
}
$(document).ready(function() {
	loadType('');
	$("#btnSave").bind('click', function() {
		saveData();
	});
	$("#btnReset").bind('click', function() {
		resetForm();
	});
});