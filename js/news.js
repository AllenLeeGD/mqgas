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
	document.getElementById("aTel").addEventListener("tap", function(e) {
		document.location.href = "tel:13169666888";
	});
	document.getElementById("aHome").addEventListener("tap", function(e) {
		document.location.href = "index.html";
	});
	document.getElementById("aNews").addEventListener("tap", function(e) {
		document.location.href = "news.html";
	});
	document.getElementById("aService").addEventListener("tap", function(e) {
		document.location.href = "service.html";
	});
	document.getElementById("aAccount").addEventListener("tap", function(e) {
		document.location.href = "account.html";
	});
/**
 * 下拉刷新具体业务实现
*/
function pulldownRefresh() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var url = '/index.php/Information/Information/findInformationByPage/';
	var params = {};
	params.startindex = 0;//起始记录
	params.pagesize = 10; //每次刷新的记录数
	mui.ajax(edu_host+url, {
		type: 'post',
		data:params,
		timeout:10000,
		success: function(data) {
			try{
				if(data[0] ==null || data[0].length==0){
					document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
					return false;
				}
                if(data[0] !=null && data[0].length>0){
                    var icount = data[0].length;
                    var sHtml = "";
                    for (var i = 0; i < icount; i++) {
                        var item = data[0][i];
                        var template = document.getElementById('tpl_list').innerHTML;
                        var title = item.title;
                        var addtime = item.addtime;
                        var pkid = item.pkid;
                        var content = item.remark;
                        var imgpath = item.imgpath;
                        if(util.isNullStr(imgpath)){
                        		imgpath = "<img class=\"mui-media-object mui-pull-left\" style=\"width:65px;height:55px;\" src=\""+edu_host+"/images/nopic.png\">";
                        }else{
                        		imgpath = "<img class=\"mui-media-object mui-pull-left\" style=\"width:65px;height:55px;\" src=\""+edu_host+"/Upload/"+imgpath+"\">";
                        }
                        template = template.replace('\$\{title\}', title);
                        template = template.replace('\$\{addtime\}', addtime);
                        template = template.replace('\$\{pkid\}', pkid);
                        template = template.replace('\$\{content\}', content);
                        template = template.replace('\$\{imgpath\}', imgpath);
                        sHtml = sHtml + template;
                    }
                    document.getElementById('div_list').innerHTML = sHtml;
                }else{
                		document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">没有任何数据</span></li>";
                }
                document.getElementById('startindex').innerHTML = data[1];
           }catch(err){
           		document.getElementById('div_list').innerHTML = "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">无法加载数据</span></li>";
           		
           }finally{
           		var objlist = document.getElementsByName('postlink');
           		for(var i=0;i<objlist.length;i++){
           			objlist[i].addEventListener("tap", function() {
           				var pkid = this.getAttribute('data');
						document.location.href = "newsdetail.html?pkid="+pkid;
					});
           		}
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
};
/**
 * 上拉加载具体业务实现
*/
function pullupRefresh() {
	var util = new Util();
	var url = '/index.php/Information/Information/findInformationByPage/';
	var params = {};
	params.startindex = document.getElementById('startindex').innerHTML;//起始记录
	params.pagesize = 10; //每次刷新的记录数
	/*pullup*/if(params.startindex=='-1'){
		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
		return;
	}
	mui.ajax(edu_host+url, {
		type: 'post',
		data:params,
		timeout:10000,
		success: function(data) {
			try{
                //获得普通帖子数据
                if(data[0] !=null && data[0].length>0){
                    var icount = data[0].length;
                    var sOld = document.getElementById('div_list').innerHTML;
                    /*pullup*/var iIndex = sOld.lastIndexOf('</li>');
                    /*pullup*/sOld = sOld.substr(0,iIndex+5);
                    var sHtml = "";
                    for (var i = 0; i < icount; i++) {
                        var item = data[0][i];
                        var template = document.getElementById('tpl_list').innerHTML;
                        var title = item.title;
                        var addtime = item.addtime;
                        var pkid = item.pkid;
                        var content = item.content;
                        var imgpath = item.imgpath;
                        if(util.isNullStr(imgpath)){
                        		imgpath = "<img class=\"mui-media-object mui-pull-left\" style=\"width:65px;height:55px;\" src=\""+edu_host+"/images/nopic.png\">";
                        }else{
                        		imgpath = "<img class=\"mui-media-object mui-pull-left\" style=\"width:65px;height:55px;\" src=\""+edu_host+"/Upload/"+imgpath+"\">";
                        }
                        template = template.replace('\$\{title\}', title);
                        template = template.replace('\$\{addtime\}', addtime);
                        template = template.replace('\$\{pkid\}', pkid);
                        template = template.replace('\$\{content\}', content);
                        template = template.replace('\$\{imgpath\}', imgpath);
                        sHtml = sHtml + template;
                    }
                    document.getElementById('div_list').innerHTML = sOld + sHtml;
                }else{
                		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);//不允许继续下拉
                }
                document.getElementById('startindex').innerHTML = data[1]; //起始记录数
           }catch(err){
           		mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
           	 	var sOld = document.getElementById('div_list').innerHTML;
           		document.getElementById('div_list').innerHTML = sOld + "<li style=\"text-align:center;padding-top:20px;padding-bottom:20px;\"><span class=\"font-size-14 ft-grey\">加载数据出错，请下拉刷新</span></li>";
           }finally{
           		var objlist = document.getElementsByName('postlink');
           		for(var i=0;i<objlist.length;i++){
           			objlist[i].addEventListener("tap", function() {
           				var pkid = this.getAttribute('data');
						document.location.href = "newsdetail.html?pkid="+pkid;
					});
           		}
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
		}
	});
};


	
