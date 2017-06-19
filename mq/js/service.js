mui.init({
	 pullRefresh : {
        container: '#pullrefresh',
        down: {
        		contentdown : '',
        		contentover : '',
        		contentrefresh : '正在刷新...',
            callback: pulldownRefresh
        },
        up: {
        		contentdown:'',
            contentrefresh: '正在载入...',
            contentnomore:'没有数据了',
            callback: pullupRefresh
        }
    }
});
pulldownRefresh();//加载数据
mui(".mui-content").scroll();
/**
 * 加载购物车信息.
 */
function loadcart() {
	var util = new Util();
	var userid = util.getvalueincache("USER_ID");
	if (util.isNullStr(userid)) {
		document.getElementById("cartcount").innerHTML = 0;
		document.getElementById("cartsum").innerHTML = 0;
		return;
	}
	mui.ajax(edu_host + '/index.php/Product/Product/loadcartsimple/userid/' + userid, {
		type: 'post',
		success: function(data) {
			if (data) {
				document.getElementById("cartcount").innerHTML = data.count;
				document.getElementById("cartsum").innerHTML = data.sum;
			} else {
				document.getElementById("cartcount").innerHTML = 0;
				document.getElementById("cartsum").innerHTML = 0;
			}
		}
	});
}
/**
 * 加载产品分类. 
 */
function loadtype() {
	document.getElementById("lTopName").addEventListener("tap", function() {
		var Picker = new mui.PopPicker();
		mui.ajax(edu_host + '/index.php/Product/Product/loadProducttype', {
			type: 'post',
			success: function(data) {
				try {
					if (data != null && data.length > 0) {
						for (var i = 0; i < data.length; i++) {
							var item = data[i];
							item.value = item.pkid;
							item.text = item.typename;
						}
						data.push({
							value: "all",
							text: "全部服务"
						});
						Picker.setData(data);
						Picker.show(function(items) {
							document.getElementById('lTopName').innerHTML = items[0].text;
							document.getElementById("itemtype").value = items[0].value;
							pulldownRefresh();
							//返回 false 可以阻止选择框的关闭
							//return false;
						});
					} else {}
				} catch (err) {} finally {}
			}
		});
	});
}
/**
 * 根据课程ID加载课程信息. 
 */
function pulldownRefresh() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var p_typeid = document.getElementById("itemtype").value;
	var usertype = util.getvalueincache("USER_TYPE");
	var typeid = "";
	var urltype = util.getParam('type');
	if (util.isNullStr(p_typeid)) {
		if(util.isNullStr(urltype)){
			typeid = "all";
		}else{
			document.getElementById('lTopName').innerHTML = '游艇';
			document.getElementById("itemtype").value = urltype;
			typeid = urltype;
		}
	} else {
		typeid = p_typeid;
	}
	var params = {};
	params.startindex = 0;//起始记录
	params.pagesize = 10; //每次刷新的记录数
	mui.ajax(edu_host + '/index.php/Product/Product/loadProduct/sessionid/null/typeid/' + typeid, {
		type: 'post',
		data:params,
		timeout:10000,
		success: function(data) {
			if (data && data[0]) {
				mui.ajax('products.txt', {
					success: function(templatetext) {
						try{
							if(data[0] !=null && data[0].length>0){
								var result = "";
								for (var i = 0; i < data[0].length; i++) {
									var item = data[0][i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{pdname\}", item.pdname);
									if (usertype == user_type_normal) {
										this_temp = this_temp.replace("\$\{price\}", "￥" + item.generalprice);
										this_temp = this_temp.replace("\$\{proprice\}", item.generalprice);
										this_temp = this_temp.replace("\$\{canbuy\}", "yes");
									} else if (usertype == user_type_vip) {
										this_temp = this_temp.replace("\$\{price\}", "￥" + item.vipprice);
										this_temp = this_temp.replace("\$\{proprice\}", item.vipprice);
										this_temp = this_temp.replace("\$\{canbuy\}", "yes");
									} else {
										this_temp = this_temp.replace("\$\{price\}", "认证后可见");
										this_temp = this_temp.replace("\$\{canbuy\}", "no");
									}
									this_temp = this_temp.replace("\$\{standard\}", item.standard);
									this_temp = this_temp.replace("\$\{productid\}", item.pkid);
									this_temp = this_temp.replace("\$\{productid\}", item.pkid);
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/Upload/" + item.imgpath);
									result += this_temp;
								}
								document.getElementById("div_list").innerHTML = result;
								var eles = document.getElementsByName("postlink");
								for(var i = 0;i<eles.length;i++){
									var item = eles[i];
									item.addEventListener("tap",function(){
										var productid = this.getAttribute("productid");
										document.location.href = "servicedetail.html?productid="+productid;
									});
								}
							}else{
			                		document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			                }
							document.getElementById('startindex').innerHTML = data[1];
						}catch(e){
							document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">无法加载数据</span></li>";
						}finally{
							mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
           					mui('#pullrefresh').pullRefresh().refresh(true);
			           		util.loadingEnd();
			           }
					},
					error:function(xhr,type,errorThrown){
						document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
						util.loadingEnd();
						mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
						mui('#pullrefresh').pullRefresh().refresh(true);
					}
				});
			} else {
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}
/**
 * 上拉加载具体业务实现
*/
function pullupRefresh() {
	var util = new Util();
	var usertype = util.getvalueincache("USER_TYPE");
	var p_typeid = document.getElementById("itemtype").value;
	var typeid = "";
	var urltype = util.getParam('type');
	if (util.isNullStr(p_typeid)) {
		if(util.isNullStr(urltype)){
			typeid = "all";
		}else{
			document.getElementById('lTopName').innerHTML = '游艇';
			document.getElementById("itemtype").value = urltype;
			typeid = urltype;
		}
	} else {
		typeid = p_typeid;
	}
	var url = '/index.php/Product/Product/loadProduct/sessionid/null/typeid/' + typeid;
	var params = {};
	params.startindex = document.getElementById('startindex').innerHTML;//起始记录
	params.pagesize = 10; //每次刷新的记录数
	/*pullup*/if(params.startindex=='-1'){
		return;
	}
	mui.ajax(edu_host+url, {
		type: 'post',
		data:params,
		timeout:10000,
		success: function(data) {
			if (data && data[0]) {
				mui.ajax('products.txt', {
					success: function(templatetext) {
						try{
			                //获得普通帖子数据
			                if(data[0] !=null && data[0].length>0){
			                    var icount = data[0].length;
			                    var sOld = document.getElementById('div_list').innerHTML;
			                    /*pullup*/var iIndex = sOld.lastIndexOf('</li>');
			                    /*pullup*/sOld = sOld.substr(0,iIndex+5);
			                    var result = "";
								for (var i = 0; i < data[0].length; i++) {
									var item = data[0][i];
									var this_temp = templatetext;
									this_temp = this_temp.replace("\$\{pdname\}", item.pdname);
									if (usertype == user_type_normal) {
										this_temp = this_temp.replace("\$\{price\}", "￥" + item.generalprice);
										this_temp = this_temp.replace("\$\{proprice\}", item.generalprice);
										this_temp = this_temp.replace("\$\{canbuy\}", "yes");
									} else if (usertype == user_type_vip) {
										this_temp = this_temp.replace("\$\{price\}", "￥" + item.vipprice);
										this_temp = this_temp.replace("\$\{proprice\}", item.vipprice);
										this_temp = this_temp.replace("\$\{canbuy\}", "yes");
									} else {
										this_temp = this_temp.replace("\$\{price\}", "认证后可见");
										this_temp = this_temp.replace("\$\{canbuy\}", "no");
									}
									this_temp = this_temp.replace("\$\{standard\}", item.standard);
									this_temp = this_temp.replace("\$\{productid\}", item.pkid);
									this_temp = this_temp.replace("\$\{productid\}", item.pkid);
									this_temp = this_temp.replace("\$\{imgpath\}", edu_host + "/Upload/" + item.imgpath);
									result += this_temp;
								}
								document.getElementById('div_list').innerHTML = sOld + result;
								var eles = document.getElementsByName("postlink");
								for(var i = 0;i<eles.length;i++){
									var item = eles[i];
									item.addEventListener("tap",function(){
										var productid = this.getAttribute("productid");
										document.location.href = "servicedetail.html?productid="+productid;
									});
								}
			                }else{
			                		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);//不允许继续下拉
			                }
			                document.getElementById('startindex').innerHTML = data[1]; //起始记录数
			           }catch(err){
			           		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
			           	 	var sOld = document.getElementById('div_list').innerHTML;
			           		document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
			           }finally{
			           		if(data[1]==-1){//已经到最后的记录
			                		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
			                		//var sOld = document.getElementById('div_list').innerHTML;
			           			//document.getElementById('div_list').innerHTML = sOld + "<div class=\"mt-10\" style=\"text-align:center;\"><span class=\"font-size-14 ft-grey\">没有数据了</span></div>";
			                		/*pullup*///document.getElementById("a_getmore").innerHTML = "没有数据了";
			                		/*pullup*///document.getElementById("a_getmore").addEventListener("tap", function(e) {
								//});
			                }else{
			                		mui('#pullrefresh').pullRefresh().endPullupToRefresh(false);//可以继续下拉加载
			                		/*pullup*///var sOld = document.getElementById('div_list').innerHTML;
			                		/*pullup*///document.getElementById('div_list').innerHTML = sOld + "<div id=\"a_getmore\" style=\"text-align:center;padding-top:10px;padding-bottom:20px;\" class=\"ft-grey font-size-14\">显示更多</div>";
			                		/*pullup*/
			                		//if(document.getElementById("a_getmore")){
				                		//document.getElementById("a_getmore").disabled = false;
				                		/*pullup*/
				                		//document.getElementById("a_getmore").addEventListener("tap", function(e) {
				                			//document.getElementById("a_getmore").disabled = true;
										//document.getElementById("a_getmore").innerHTML = '加载中...';
										//pullupRefresh();
									//});
								//}
			                }
			               
			           }
					},
					error:function(xhr,type,errorThrown){
						mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
						var sOld = document.getElementById('div_list').innerHTML;
			           	document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错</span></li>";
					}//end error
				});//end load txt
			}else{
				document.getElementById("div_list").innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
			}
		}
	});
}
(function($, doc) {
	$.ready(function() {
		var util = new Util();
		mui.init();
		mui(".mui-scroll-wrapper").scroll();
		document.getElementById("aCart").addEventListener("tap", function(e) {
			document.location.href = "cart.html";
		});
		document.getElementById("btnCart").addEventListener("tap", function(e) {
			document.location.href = "cart.html";
		});
		var btnArray = ['确定', '取消'];
		$('#div_list').on('tap', '.mui-btn', function(event) {
			var elem = this;
			var li = elem.parentNode.parentNode;
			var proid = elem.getAttribute('productid');
			var proprice = elem.getAttribute('proprice');
			var canbuy = elem.getAttribute('canbuy');
			if("no"==canbuy){
				mui.toast("请先进行认证");
				return;
			}
			mui.confirm('确定将放入购物车？', '购买', btnArray, function(e) {
				if (e.index == 0) {
					//放入购物车方法
					var userid = util.getvalueincache("USER_ID");
					if (util.isNullStr(userid)) {
						mui.toast("请登录");
					} else {
						mui.ajax(edu_host + '/index.php/Product/Product/addProduct2Cart/userid/' + userid + "/productid/" + proid + "/price/" + proprice, {
							type: 'post',
							success: function(data) {
								try {
									if (data == "yes") {
										mui.toast("成功加入购物车");
										loadcart();
									} else {
										mui.toast("加入购物车失败");
									}
								} catch (err) {} finally {}
							}
						});
					}
				} else {
					setTimeout(function() {
						$.swipeoutClose(li);
					}, 0);
				}
			});
		});
		loadtype();
		loadcart();
	});
})(mui, document);