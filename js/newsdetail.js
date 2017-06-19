function findNewsByPkid() {
	var util = new Util();
	util.loadingmessage('加载中...');
	var pkid = util.getParam("pkid");
	mui.ajax(edu_host + '/index.php/Information/Information/findInformationByPkid/pkid/' + pkid, {
		type: 'post',
		success: function(data) {
			if (data) {
				var template = document.getElementById('tpl_list').innerHTML;
                var title = data.title;
                var addtime = data.addtime;
                var pkid = data.pkid;
                var content = data.content;
                var imgpath = data.imgpath;
                if(util.isNullStr(imgpath)){
					imgpath = "<img style=\"width:100%;\" src=\""+edu_host+"/images/nopic.jpg\">"
				}else{
					imgpath = "<img style=\"width:100%;\" src=\""+edu_host+"/Upload/"+imgpath+"\">";
				}
                template = template.replace('\$\{title\}', title);
                template = template.replace('\$\{addtime\}', addtime);
                template = template.replace('\$\{pkid\}', pkid);
                template = template.replace('\$\{content\}', content);
                template = template.replace('\$\{imgpath\}', imgpath);
				document.getElementById('div_list').innerHTML = template;
			} else {
				mui.toast("<span class=\"mui-icon ion-ios-minus-outline\"></span><br />加载出错");
			}
		},
		complete:function(){
			util.loadingEnd();
		}
	});
}
(function($, doc) {
	$.ready(function() {
		mui.init();
		mui(".mui-scroll-wrapper").scroll();
		document.getElementById("aHome").addEventListener("tap", function(e) {
			document.location.href = "index.html";
		});
		findNewsByPkid();
	});
})(mui, document);