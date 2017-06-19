//var edu_host = "http://120.25.204.117/edu_new_web"
//var edu_host = "http://newoceangas.cn/";  
var edu_host = "http://localhost:8066/";
//var edu_host = "http://www.kingleclub.com";
//var edu_prefix = "/edu_new_web/";
var edu_prefix = "";
var user_type_vip = "vip_user";
var user_type_normal = "normal_user";
var user_type_new = "new_user";
var user_type_verifying = "verifying_user";
var user_type_refuse = "refuse_user";

function checkUserLogin(){
	var userid = sessionStorage.getItem("USER_ID");
	if(userid==null || userid=="" || userid==undefined){
		document.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc49b96e815af547c&redirect_uri=http%3A%2F%2Fnewoceangas.cn%2Fnative%2Fwxback.php&response_type=code&scope=snsapi_userinfo&state=11#wechat_redirect";
	}
}
//checkUserLogin();
/**
 * 四舍五入后保留两位小数. 
 * @param {Object} x
 */
function toDecimal(x) {
	var f = parseFloat(x);
	if (isNaN(f)) {
		return;
	}
	f = Math.round(x * 100) / 100;
	return f;
}
/**
 * 虚拟表单提交. 
 * @param {Object} URL 提交地址.
 * @param {Object} PARAMS JSON格式的参数.
 */
function post2url(URL, PARAMS) {
	var temp = document.createElement("form");
	temp.action = URL;
	temp.method = "post";
	temp.style.display = "none";
	for (var x in PARAMS) {
		var opt = document.createElement("input");
		opt.name = x;
		opt.value = PARAMS[x];
		// alert(opt.name)        
		temp.appendChild(opt);
	}
	document.body.appendChild(temp);
	temp.submit();
	return temp;
}

/**  
 * UTF16和UTF8转换对照表
 * U+00000000 – U+0000007F   0xxxxxxx
 * U+00000080 – U+000007FF   110xxxxx 10xxxxxx
 * U+00000800 – U+0000FFFF   1110xxxx 10xxxxxx 10xxxxxx
 * U+00010000 – U+001FFFFF   11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
 * U+00200000 – U+03FFFFFF   111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
 * U+04000000 – U+7FFFFFFF   1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
 */
function doBack() {
	var util = new Util();
	var pg = util.getParam("pg");
	var fd = util.getParam("fd");
	if (!util.isNullStr(pg) && !util.isNullStr(fd)) {
		document.location.href = "../" + fd + "/" + pg;
	} else {
		window.history.back();
	}
}
var Base64 = {
	// 转码表  
	table: [
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
		'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
		'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
		'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
		'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
		'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
		'w', 'x', 'y', 'z', '0', '1', '2', '3',
		'4', '5', '6', '7', '8', '9', '+', '/'
	],
	UTF16ToUTF8: function(str) {
		var res = [],
			len = str.length;
		for (var i = 0; i < len; i++) {
			var code = str.charCodeAt(i);
			if (code > 0x0000 && code <= 0x007F) {
				// 单字节，这里并不考虑0x0000，因为它是空字节  
				// U+00000000 – U+0000007F  0xxxxxxx  
				res.push(str.charAt(i));
			} else if (code >= 0x0080 && code <= 0x07FF) {
				// 双字节  
				// U+00000080 – U+000007FF  110xxxxx 10xxxxxx  
				// 110xxxxx  
				var byte1 = 0xC0 | ((code >> 6) & 0x1F);
				// 10xxxxxx  
				var byte2 = 0x80 | (code & 0x3F);
				res.push(
					String.fromCharCode(byte1),
					String.fromCharCode(byte2)
				);
			} else if (code >= 0x0800 && code <= 0xFFFF) {
				// 三字节  
				// U+00000800 – U+0000FFFF  1110xxxx 10xxxxxx 10xxxxxx  
				// 1110xxxx  
				var byte1 = 0xE0 | ((code >> 12) & 0x0F);
				// 10xxxxxx  
				var byte2 = 0x80 | ((code >> 6) & 0x3F);
				// 10xxxxxx  
				var byte3 = 0x80 | (code & 0x3F);
				res.push(
					String.fromCharCode(byte1),
					String.fromCharCode(byte2),
					String.fromCharCode(byte3)
				);
			} else if (code >= 0x00010000 && code <= 0x001FFFFF) {
				// 四字节  
				// U+00010000 – U+001FFFFF  11110xxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else if (code >= 0x00200000 && code <= 0x03FFFFFF) {
				// 五字节  
				// U+00200000 – U+03FFFFFF  111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else /** if (code >= 0x04000000 && code <= 0x7FFFFFFF)*/ {
				// 六字节  
				// U+04000000 – U+7FFFFFFF  1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx  
			}
		}

		return res.join('');
	},
	UTF8ToUTF16: function(str) {
		var res = [],
			len = str.length;
		var i = 0;
		for (var i = 0; i < len; i++) {
			var code = str.charCodeAt(i);
			// 对第一个字节进行判断  
			if (((code >> 7) & 0xFF) == 0x0) {
				// 单字节  
				// 0xxxxxxx  
				res.push(str.charAt(i));
			} else if (((code >> 5) & 0xFF) == 0x6) {
				// 双字节  
				// 110xxxxx 10xxxxxx  
				var code2 = str.charCodeAt(++i);
				var byte1 = (code & 0x1F) << 6;
				var byte2 = code2 & 0x3F;
				var utf16 = byte1 | byte2;
				res.push(Sting.fromCharCode(utf16));
			} else if (((code >> 4) & 0xFF) == 0xE) {
				// 三字节  
				// 1110xxxx 10xxxxxx 10xxxxxx  
				var code2 = str.charCodeAt(++i);
				var code3 = str.charCodeAt(++i);
				var byte1 = (code << 4) | ((code2 >> 2) & 0x0F);
				var byte2 = ((code2 & 0x03) << 6) | (code3 & 0x3F);
				utf16 = ((byte1 & 0x00FF) << 8) | byte2
				res.push(String.fromCharCode(utf16));
			} else if (((code >> 3) & 0xFF) == 0x1E) {
				// 四字节  
				// 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else if (((code >> 2) & 0xFF) == 0x3E) {
				// 五字节  
				// 111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else /** if (((code >> 1) & 0xFF) == 0x7E)*/ {
				// 六字节  
				// 1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx  
			}
		}

		return res.join('');
	},
	encode: function(str) {
		if (!str) {
			return '';
		}
		var utf8 = this.UTF16ToUTF8(str); // 转成UTF8  
		var i = 0; // 遍历索引  
		var len = utf8.length;
		var res = [];
		while (i < len) {
			var c1 = utf8.charCodeAt(i++) & 0xFF;
			res.push(this.table[c1 >> 2]);
			// 需要补2个=  
			if (i == len) {
				res.push(this.table[(c1 & 0x3) << 4]);
				res.push('==');
				break;
			}
			var c2 = utf8.charCodeAt(i++);
			// 需要补1个=  
			if (i == len) {
				res.push(this.table[((c1 & 0x3) << 4) | ((c2 >> 4) & 0x0F)]);
				res.push(this.table[(c2 & 0x0F) << 2]);
				res.push('=');
				break;
			}
			var c3 = utf8.charCodeAt(i++);
			res.push(this.table[((c1 & 0x3) << 4) | ((c2 >> 4) & 0x0F)]);
			res.push(this.table[((c2 & 0x0F) << 2) | ((c3 & 0xC0) >> 6)]);
			res.push(this.table[c3 & 0x3F]);
		}

		return res.join('');
	},
	decode: function(str) {
		if (!str) {
			return '';
		}

		var len = str.length;
		var i = 0;
		var res = [];

		while (i < len) {
			code1 = this.table.indexOf(str.charAt(i++));
			code2 = this.table.indexOf(str.charAt(i++));
			code3 = this.table.indexOf(str.charAt(i++));
			code4 = this.table.indexOf(str.charAt(i++));

			c1 = (code1 << 2) | (code2 >> 4);
			c2 = ((code2 & 0xF) << 4) | (code3 >> 2);
			c3 = ((code3 & 0x3) << 6) | code4;

			res.push(String.fromCharCode(c1));

			if (code3 != 64) {
				res.push(String.fromCharCode(c2));
			}
			if (code4 != 64) {
				res.push(String.fromCharCode(c3));
			}

		}

		return this.UTF8ToUTF16(res.join(''));
	}
};
Date.prototype.Format = function(fmt) { //author: meizz 
		var o = {
			"M+": this.getMonth() + 1, //月份 
			"d+": this.getDate(), //日 
			"h+": this.getHours(), //小时 
			"m+": this.getMinutes(), //分 
			"s+": this.getSeconds(), //秒 
			"q+": Math.floor((this.getMonth() + 3) / 3), //季度 
			"S": this.getMilliseconds() //毫秒 
		};
		if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
		for (var k in o)
			if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		return fmt;
	}
	/**
	 * 检查图片类型. 
	 * @img 图片名称
	 */
function checkImgType(img) {
	if (!(img.endsWith("jpg") || img.endsWith("png") || img.endsWith("gif") || img.endsWith("jpeg"))) {
		mui.toast('图片类型错误');
		setTimeout(function() {
			document.location.reload();
		}, 1000);
		return false;
	}
	return true;
}
/* 
 *  方法:Array.remove(dx) 通过遍历,重构数组 
 *  功能:删除数组元素. 
 *  参数:dx删除元素的下标. 
 */
Array.prototype.remove = function(dx) {
	if (isNaN(dx) || dx > this.length) {
		return false;
	}
	for (var i = 0, n = 0; i < this.length; i++) {
		if (this[i] != this[dx]) {
			this[n++] = this[i]
		}
	}
	this.length -= 1
}

function isEmail(str) {
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	return reg.test(str);
}

function isMobile(str) {
	var reg = /^1[3|4|5|7|8][0-9]\d{4,8}$/;
	return reg.test(str);
}
//正整数和0
function isInteger(str) {
	var reg = /^([1-9]\d*|[0]{1,1})$/;
	return reg.test(str);
}
//0,正整数,正浮点数
function isNumber(str) {
	var reg = /^\+?\d+(.\d+)?$/;
	return reg.test(str);
}
//密码（字母数字6-20位）
function isPassword(str) {
	var reg = /^(?![^a-zA-Z]+$)(?!\D+$).{6,20}$/;
	return reg.test(str);
}

function getCookie(name) {
	var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
	if (arr = document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}
//检查图片后缀是否合法
function checkpic(picurl) {
	if (picurl == "") return true;
	var strTemp = picurl.substr(picurl.lastIndexOf(".")).toLowerCase(),
		//全部图片格式类型 
		AllImgExt = ".jpg|.jpeg|.gif|.png|";

	if (AllImgExt.indexOf(strTemp + "|") != -1) {
		return false;
	} else {
		return true;
	}
}

function getFileSize(fileid) {
	var fileInput = document.getElementById(fileid);
	try {
		byteSize = fileInput.files[0].size;
		return (Math.ceil(byteSize / (1024 * 1024))); // Size returned in MB.
	} catch (e) {
		return 0;
	}
}
//检查图片后缀是否合法
function checkpicAndSize(picurl, fileid) {
	var standard = 10; //2MB
	if (picurl == "") return true;
	var strTemp = picurl.substr(picurl.lastIndexOf(".")).toLowerCase(),
		//全部图片格式类型 
		AllImgExt = ".jpg|.jpeg|.gif|.png|";
	var size = getFileSize(fileid);
	if (AllImgExt.indexOf(strTemp + "|") == -1) {
		return "picfalse";
	} else if (size > standard || size == 0) {
		return "sizefalse";
	} else {
		return true;
	}
}
//检查视频后缀是否合法
function checkVideoAndSize(picurl, fileid) {
	var standard = 200; //2MB
	if (picurl == "") return true;
	var strTemp = picurl.substr(picurl.lastIndexOf(".")).toLowerCase(),
		//全部图片格式类型 
		AllImgExt = ".mp4|.MP4";
	var size = getFileSize(fileid);
	if (AllImgExt.indexOf(strTemp + "|") == -1) {
		return "picfalse";
	} else if (size > standard || size == 0) {
		return "sizefalse";
	} else {
		return true;
	}
}
//身份证号码合法性检查
function IdentityCodeValid(code) {
	var city = {
		11: "北京",
		12: "天津",
		13: "河北",
		14: "山西",
		15: "内蒙古",
		21: "辽宁",
		22: "吉林",
		23: "黑龙江 ",
		31: "上海",
		32: "江苏",
		33: "浙江",
		34: "安徽",
		35: "福建",
		36: "江西",
		37: "山东",
		41: "河南",
		42: "湖北 ",
		43: "湖南",
		44: "广东",
		45: "广西",
		46: "海南",
		50: "重庆",
		51: "四川",
		52: "贵州",
		53: "云南",
		54: "西藏 ",
		61: "陕西",
		62: "甘肃",
		63: "青海",
		64: "宁夏",
		65: "新疆",
		71: "台湾",
		81: "香港",
		82: "澳门",
		91: "国外 "
	};
	var tip = "";
	var pass = true;

	if (!code || !/^[1-9][0-9]{5}(19[0-9]{2}|200[0-9]|2010)(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[0-9]{3}[0-9xX]$/i.test(code)) {
		pass = false;
	} else if (!city[code.substr(0, 2)]) {
		pass = false;
	} else {
		//18位身份证需要验证最后一位校验位
		if (code.length == 18) {
			code = code.split('');
			//∑(ai×Wi)(mod 11)
			//加权因子
			var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
			//校验位
			var parity = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2];
			var sum = 0;
			var ai = 0;
			var wi = 0;
			for (var i = 0; i < 17; i++) {
				ai = code[i];
				wi = factor[i];
				sum += ai * wi;
			}
			var last = parity[sum % 11];
			if (parity[sum % 11] != code[17]) {
				pass = false;
			}
		}
	}
	return pass;
}

/* 
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Copyright (C) Paul Johnston 1999 - 2000.
 * Updated by Greg Holt 2000 - 2001.
 * See http://pajhome.org.uk/site/legal.html for details.
 */

/* 
 * Convert a 32-bit number to a hex string with ls-byte first
 */
var hex_chr = "0123456789abcdef";

function rhex(num) {
	str = "";
	for (j = 0; j <= 3; j++)
		str += hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
		hex_chr.charAt((num >> (j * 8)) & 0x0F);
	return str;
}

/* 
 * Convert a string to a sequence of 16-word blocks, stored as an array.
 * Append padding bits and the length, as described in the MD5 standard.
 */
function str2blks_MD5(str) {
	nblk = ((str.length + 8) >> 6) + 1;
	blks = new Array(nblk * 16);
	for (i = 0; i < nblk * 16; i++) blks[i] = 0;
	for (i = 0; i < str.length; i++)
		blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
	blks[i >> 2] |= 0x80 << ((i % 4) * 8);
	blks[nblk * 16 - 2] = str.length * 8;
	return blks;
}

/* 
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function add(x, y) {
	var lsw = (x & 0xFFFF) + (y & 0xFFFF);
	var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
	return (msw << 16) | (lsw & 0xFFFF);
}

/* 
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt) {
	return (num << cnt) | (num >>> (32 - cnt));
}

/* 
 * These functions implement the basic operation for each round of the
 * algorithm.
 */
function cmn(q, a, b, x, s, t) {
	return add(rol(add(add(a, q), add(x, t)), s), b);
}

function ff(a, b, c, d, x, s, t) {
	return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
	return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
	return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
	return cmn(c ^ (b | (~d)), a, b, x, s, t);
}
/* 
 * Take a string and return the hex representation of its MD5.
 */
function calcMD5(str) {
	x = str2blks_MD5(str);
	a = 1732584193;
	b = -271733879;
	c = -1732584194;
	d = 271733878;

	for (i = 0; i < x.length; i += 16) {
		olda = a;
		oldb = b;
		oldc = c;
		oldd = d;

		a = ff(a, b, c, d, x[i + 0], 7, -680876936);
		d = ff(d, a, b, c, x[i + 1], 12, -389564586);
		c = ff(c, d, a, b, x[i + 2], 17, 606105819);
		b = ff(b, c, d, a, x[i + 3], 22, -1044525330);
		a = ff(a, b, c, d, x[i + 4], 7, -176418897);
		d = ff(d, a, b, c, x[i + 5], 12, 1200080426);
		c = ff(c, d, a, b, x[i + 6], 17, -1473231341);
		b = ff(b, c, d, a, x[i + 7], 22, -45705983);
		a = ff(a, b, c, d, x[i + 8], 7, 1770035416);
		d = ff(d, a, b, c, x[i + 9], 12, -1958414417);
		c = ff(c, d, a, b, x[i + 10], 17, -42063);
		b = ff(b, c, d, a, x[i + 11], 22, -1990404162);
		a = ff(a, b, c, d, x[i + 12], 7, 1804603682);
		d = ff(d, a, b, c, x[i + 13], 12, -40341101);
		c = ff(c, d, a, b, x[i + 14], 17, -1502002290);
		b = ff(b, c, d, a, x[i + 15], 22, 1236535329);

		a = gg(a, b, c, d, x[i + 1], 5, -165796510);
		d = gg(d, a, b, c, x[i + 6], 9, -1069501632);
		c = gg(c, d, a, b, x[i + 11], 14, 643717713);
		b = gg(b, c, d, a, x[i + 0], 20, -373897302);
		a = gg(a, b, c, d, x[i + 5], 5, -701558691);
		d = gg(d, a, b, c, x[i + 10], 9, 38016083);
		c = gg(c, d, a, b, x[i + 15], 14, -660478335);
		b = gg(b, c, d, a, x[i + 4], 20, -405537848);
		a = gg(a, b, c, d, x[i + 9], 5, 568446438);
		d = gg(d, a, b, c, x[i + 14], 9, -1019803690);
		c = gg(c, d, a, b, x[i + 3], 14, -187363961);
		b = gg(b, c, d, a, x[i + 8], 20, 1163531501);
		a = gg(a, b, c, d, x[i + 13], 5, -1444681467);
		d = gg(d, a, b, c, x[i + 2], 9, -51403784);
		c = gg(c, d, a, b, x[i + 7], 14, 1735328473);
		b = gg(b, c, d, a, x[i + 12], 20, -1926607734);

		a = hh(a, b, c, d, x[i + 5], 4, -378558);
		d = hh(d, a, b, c, x[i + 8], 11, -2022574463);
		c = hh(c, d, a, b, x[i + 11], 16, 1839030562);
		b = hh(b, c, d, a, x[i + 14], 23, -35309556);
		a = hh(a, b, c, d, x[i + 1], 4, -1530992060);
		d = hh(d, a, b, c, x[i + 4], 11, 1272893353);
		c = hh(c, d, a, b, x[i + 7], 16, -155497632);
		b = hh(b, c, d, a, x[i + 10], 23, -1094730640);
		a = hh(a, b, c, d, x[i + 13], 4, 681279174);
		d = hh(d, a, b, c, x[i + 0], 11, -358537222);
		c = hh(c, d, a, b, x[i + 3], 16, -722521979);
		b = hh(b, c, d, a, x[i + 6], 23, 76029189);
		a = hh(a, b, c, d, x[i + 9], 4, -640364487);
		d = hh(d, a, b, c, x[i + 12], 11, -421815835);
		c = hh(c, d, a, b, x[i + 15], 16, 530742520);
		b = hh(b, c, d, a, x[i + 2], 23, -995338651);

		a = ii(a, b, c, d, x[i + 0], 6, -198630844);
		d = ii(d, a, b, c, x[i + 7], 10, 1126891415);
		c = ii(c, d, a, b, x[i + 14], 15, -1416354905);
		b = ii(b, c, d, a, x[i + 5], 21, -57434055);
		a = ii(a, b, c, d, x[i + 12], 6, 1700485571);
		d = ii(d, a, b, c, x[i + 3], 10, -1894986606);
		c = ii(c, d, a, b, x[i + 10], 15, -1051523);
		b = ii(b, c, d, a, x[i + 1], 21, -2054922799);
		a = ii(a, b, c, d, x[i + 8], 6, 1873313359);
		d = ii(d, a, b, c, x[i + 15], 10, -30611744);
		c = ii(c, d, a, b, x[i + 6], 15, -1560198380);
		b = ii(b, c, d, a, x[i + 13], 21, 1309151649);
		a = ii(a, b, c, d, x[i + 4], 6, -145523070);
		d = ii(d, a, b, c, x[i + 11], 10, -1120210379);
		c = ii(c, d, a, b, x[i + 2], 15, 718787259);
		b = ii(b, c, d, a, x[i + 9], 21, -343485551);

		a = add(a, olda);
		b = add(b, oldb);
		c = add(c, oldc);
		d = add(d, oldd);
	}
	return rhex(a) + rhex(b) + rhex(c) + rhex(d);
}

function base64_encode(str) {
	var str = toUTF8(str);
	var base64EncodeChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
	var out, i, j, len, r, l, c;
	i = j = 0;
	len = str.length;
	r = len % 3;
	len = len - r;
	l = (len / 3) << 2;
	if (r > 0) {
		l += 4;
	}
	out = new Array(l);

	while (i < len) {
		c = str.charCodeAt(i++) << 16 |
			str.charCodeAt(i++) << 8 |
			str.charCodeAt(i++);
		out[j++] = base64EncodeChars[c >> 18] + base64EncodeChars[c >> 12 & 0x3f] + base64EncodeChars[c >> 6 & 0x3f] + base64EncodeChars[c & 0x3f];
	}
	if (r == 1) {
		c = str.charCodeAt(i++);
		out[j++] = base64EncodeChars[c >> 2] + base64EncodeChars[(c & 0x03) << 4] + "==";
	} else if (r == 2) {
		c = str.charCodeAt(i++) << 8 |
			str.charCodeAt(i++);
		out[j++] = base64EncodeChars[c >> 10] + base64EncodeChars[c >> 4 & 0x3f] + base64EncodeChars[(c & 0x0f) << 2] + "=";
	}
	return out.join('');
}

function base64_decode(str) {
	var base64DecodeChars = [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
		52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
		15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
		41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1
	];
	var c1, c2, c3, c4;
	var i, j, len, r, l, out;

	len = str.length;
	if (len % 4 != 0) {
		return '';
	}
	if (/[^ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\+\/\=]/.test(str)) {
		return '';
	}
	if (str.charAt(len - 2) == '=') {
		r = 1;
	} else if (str.charAt(len - 1) == '=') {
		r = 2;
	} else {
		r = 0;
	}
	l = len;
	if (r > 0) {
		l -= 4;
	}
	l = (l >> 2) * 3 + r;
	out = new Array(l);

	i = j = 0;
	while (i < len) {
		// c1
		c1 = base64DecodeChars[str.charCodeAt(i++)];
		if (c1 == -1) break;

		// c2
		c2 = base64DecodeChars[str.charCodeAt(i++)];
		if (c2 == -1) break;

		out[j++] = String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

		// c3
		c3 = base64DecodeChars[str.charCodeAt(i++)];
		if (c3 == -1) break;

		out[j++] = String.fromCharCode(((c2 & 0x0f) << 4) | ((c3 & 0x3c) >> 2));

		// c4
		c4 = base64DecodeChars[str.charCodeAt(i++)];
		if (c4 == -1) break;

		out[j++] = String.fromCharCode(((c3 & 0x03) << 6) | c4);
	}
	return toUTF16(out.join(''));
}

function toUTF8(str) {
	if (str.match(/^[\x00-\x7f]*$/) != null) {
		return str.toString();
	}
	var out, i, j, len, c, c2;
	out = [];
	len = str.length;
	for (i = 0, j = 0; i < len; i++, j++) {
		c = str.charCodeAt(i);
		if (c <= 0x7f) {
			out[j] = str.charAt(i);
		} else if (c <= 0x7ff) {
			out[j] = String.fromCharCode(0xc0 | (c >>> 6),
				0x80 | (c & 0x3f));
		} else if (c < 0xd800 || c > 0xdfff) {
			out[j] = String.fromCharCode(0xe0 | (c >>> 12),
				0x80 | ((c >>> 6) & 0x3f),
				0x80 | (c & 0x3f));
		} else {
			if (++i < len) {
				c2 = str.charCodeAt(i);
				if (c <= 0xdbff && 0xdc00 <= c2 && c2 <= 0xdfff) {
					c = ((c & 0x03ff) << 10 | (c2 & 0x03ff)) + 0x010000;
					if (0x010000 <= c && c <= 0x10ffff) {
						out[j] = String.fromCharCode(0xf0 | ((c >>> 18) & 0x3f),
							0x80 | ((c >>> 12) & 0x3f),
							0x80 | ((c >>> 6) & 0x3f),
							0x80 | (c & 0x3f));
					} else {
						out[j] = '?';
					}
				} else {
					i--;
					out[j] = '?';
				}
			} else {
				i--;
				out[j] = '?';
			}
		}
	}
	return out.join('');
}

function toUTF16(str) {
	if ((str.match(/^[\x00-\x7f]*$/) != null) ||
		(str.match(/^[\x00-\xff]*$/) == null)) {
		return str.toString();
	}
	var out, i, j, len, c, c2, c3, c4, s;

	out = [];
	len = str.length;
	i = j = 0;
	while (i < len) {
		c = str.charCodeAt(i++);
		switch (c >> 4) {
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				// 0xxx xxxx
				out[j++] = str.charAt(i - 1);
				break;
			case 12:
			case 13:
				// 110x xxxx   10xx xxxx
				c2 = str.charCodeAt(i++);
				out[j++] = String.fromCharCode(((c & 0x1f) << 6) |
					(c2 & 0x3f));
				break;
			case 14:
				// 1110 xxxx  10xx xxxx  10xx xxxx
				c2 = str.charCodeAt(i++);
				c3 = str.charCodeAt(i++);
				out[j++] = String.fromCharCode(((c & 0x0f) << 12) |
					((c2 & 0x3f) << 6) |
					(c3 & 0x3f));
				break;
			case 15:
				switch (c & 0xf) {
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
						// 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
						c2 = str.charCodeAt(i++);
						c3 = str.charCodeAt(i++);
						c4 = str.charCodeAt(i++);
						s = ((c & 0x07) << 18) |
							((c2 & 0x3f) << 12) |
							((c3 & 0x3f) << 6) |
							(c4 & 0x3f) - 0x10000;
						if (0 <= s && s <= 0xfffff) {
							out[j++] = String.fromCharCode(((s >>> 10) & 0x03ff) | 0xd800, (s & 0x03ff) | 0xdc00);
						} else {
							out[j++] = '?';
						}
						break;
					case 8:
					case 9:
					case 10:
					case 11:
						// 1111 10xx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
						i += 4;
						out[j++] = '?';
						break;
					case 12:
					case 13:
						// 1111 110x  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
						i += 5;
						out[j++] = '?';
						break;
				}
		}
	}
	return out.join('');
}

function getUrlPageName() {
	var strUrl = window.location.href;
	var arrUrl = strUrl.split("/");
	var strPage = arrUrl[arrUrl.length - 1];
	return strPage;
}

function _setTimer(num, btnid, wait, secs) {
	if (num == wait) {
		btnid.innerHTML = "发送验证码";
		btnid.disabled = false;

	} else {
		secs = wait - num;
		btnid.innerHTML = secs + "秒";
	}
}

var Util = function() {
	this.toast = null;
	this.loadingmessage = function(message) {
		if (mui.os.plus) {
			//默认显示在底部；
			mui.plusReady(function() {
				plus.nativeUI.toast(message, {
					verticalAlign: 'bottom'
				});
			});
		} else {
			this.toast = document.createElement('div');
			this.toast.classList.add('mui-toast-container');
			this.toast.innerHTML = '<div class="' + 'mui-toast-message' + '">' + "<img src=\"" + edu_host + "/images/load.gif\" style=\"width:40px;height:40px;\"><br />" + message + '</div>';
			document.body.appendChild(this.toast);
		}
	}
	this.loadingEnd = function() {
		document.body.removeChild(this.toast);
	}
	this.timer = function(btnid) {
			var wait = 120;
			var secs = 0;
			document.getElementById(btnid).disabled = true;
			for (var i = 1; i <= wait; i++) {
				window.setTimeout("_setTimer(" + i + "," + btnid + "," + wait + "," + secs + ")", i * 1000);
			}
		}
		//将服务器返回的sessionid放入本地，根据不同情况，如果是webapp则放入cookie，如果是app则放入本地硬盘plus.storage.setItem(_user_token,_usertoken);
	this.putsessioinid = function(sessionid) {
		//		document.cookie = "EDU_SESSIONID=" + sessionid + ";expires=1800";
		localStorage.setItem("EDU_SESSIONID", sessionid);
	}
	this.getsessionid = function() {
			//		return getCookie("EDU_SESSIONID");
			return localStorage.getItem("EDU_SESSIONID");
		}
		/**
		 * 向本地的session中存放数值.
		 */
	this.putvalueinsession = function(key, value) {
			localStorage.setItem(key, value);
		}
		/**
		 * 从本地的session中取数值.
		 */
	this.getvalueinsession = function(key) {
			return localStorage.getItem(key);
		}
		/**
		 * 向本地的session中存放数值.
		 */
	this.putvalueincache = function(key, value) {
			sessionStorage.setItem(key, value);
		}
		/**
		 * 从本地的session中取数值.
		 */
	this.getvalueincache = function(key) {
			return sessionStorage.getItem(key);
		}
		/**
		 * 删除key. 
		 */
	this.removekeyincache = function(key) {
			return sessionStorage.removeItem(key);
		}
		/**
		 * 显示loading.
		 */
	this.showloading = function() {
			document.getElementById('div_loading').style.display = 'block';
		}
		/**
		 * 隐藏loading.
		 */
	this.hideloading = function() {
			document.getElementById('div_loading').style.display = 'none';
		}
		//检查session
	this.checkSession = function(tourl) {
			var util = new Util();
			mui.ajax(edu_host + '/index.php/Register/Register/checkSession', {
				data: {
					"sessionid": util.getsessionid()
				},
				type: 'post',
				success: function(data) {
					if (data == "notlogin") {
						util.putsessioinid('');
						document.location.href = "../register/login.html?jumpurl=" + tourl;
					}
				}
			});
		}
		//从URL获取参数值
	this.getParam = function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");

		var r = window.location.search.substr(1).match(reg);

		if (r != null) return unescape(r[2]);

		return null;
	}
	this.strencode = function(string, encrypt) {
		key = calcMD5(encrypt);
		string = Base64.decode(string);
		len = key.length;
		code = '';
		for (i = 0; i < string.length; i++) {
			k = i % len;
			code += String.fromCharCode(string.charCodeAt(i) ^ key.charCodeAt(k));
		}
		return Base64.decode(code);
	}
	this.isNullStr = function(str) {
		if (str == null) {
			return true;
		} else if (str == "") {
			return true;
		} else if (str == undefined) {
			return true;
		} else if (str == "null") {
			return true;
		} else {
			return false;
		}
	}
	this.post2url = function(POSTURL, PARAMS) {
		var temp = document.getElementById("dynamicForm");
		temp.setAttribute("method",'post');
		temp.setAttribute("action",POSTURL);
		temp.innerHTML="";
		for (var i = 0;i<3;i++){
			var opt = document.createElement("input");
			opt.setAttribute("type","hidden");
			opt.setAttribute("name",PARAMS[i].name);
			opt.setAttribute("value",PARAMS[i].value);
			// alert(opt.name)        
			temp.appendChild(opt);
		}
		temp.submit();
	}
};

//后退处理
var _hide_private_util = new Util();
var back_history_lst;
var back_history_lst_str = _hide_private_util.getvalueincache("BACK_HISTORY_LST");
if (!back_history_lst_str) {
	back_history_lst = new Array();
} else {
	back_history_lst = JSON.parse(back_history_lst_str);
}
var _is_back = _hide_private_util.getParam("isback");
if (_is_back == "true") {
	back_history_lst.pop();
} else {
	var _this_location_url = document.location.href;
	var _start_pos = _this_location_url.indexOf("/client");
	var _this_page = _this_location_url.substr(_start_pos, _this_location_url.length);
	var _last_history_page = back_history_lst[back_history_lst.length - 1];
	if (_this_page != _last_history_page) {
		back_history_lst.push(_this_page);
	}
}
var new_back_history_lst_str = JSON.stringify(back_history_lst);
_hide_private_util.putvalueincache("BACK_HISTORY_LST", new_back_history_lst_str);
/**
 * 后退按钮. 
 */
if (document.getElementById("go_back_page")) {
	document.getElementById("go_back_page").addEventListener("tap", function() {
		var _go_back_lst_str = _hide_private_util.getvalueincache("BACK_HISTORY_LST");
		if (_go_back_lst_str) {
			var _go_back_lst = JSON.parse(_go_back_lst_str);
			_hide_private_util.putvalueincache("BACK_HISTORY_LST", JSON.stringify(_go_back_lst));
			var _last_page = _go_back_lst[_go_back_lst.length - 2];
			if (_last_page.indexOf("?") != -1) {
				window.location.href = edu_prefix + _last_page + "&isback=true";
			} else {
				window.location.href = edu_prefix + _last_page + "?isback=true";
			}
		}
	});
}

/**
 * 回到主页. 
 */
var _go_my_home = document.getElementById("go_back_my_home");
if (_go_my_home) {
	_go_my_home.addEventListener("tap", function() {
		var util = new Util();
		var type = util.getvalueincache("USERTYPE");
		if (type == "tutor") {
			document.location.href = "../tutor/indextutor.html";
		} else if (type == "org") {
			document.location.href = "../org/indexorg.html";
		} else if (type == "student") {
			document.location.href = "../student/indexstudent.html";
		} else if (type == "school") {
			document.location.href = "../school/homeDean.html";
		} else if (type == "teacher") {
			document.location.href = "../school/homeTeacher.html";
		}
	});
}

function checkMsg(id) {
	var util = new Util();
	var sessionid = util.getsessionid();
	if (util.isNullStr(sessionid)) {
		return;
	}
	mui.ajax(edu_host + '/index.php/Message/Message/checkUnread/sessionid/' + sessionid, {
		type: 'post',
		success: function(data) {
			var parent = document.getElementById(id);
			if (parent) {
				if (data == "yes") {
					parent.innerHTML = "<span class=\"mui-icon ion-ios-chatbubble-outline\"></span><span style=\"color:#cc0000;\">●</span><span class=\"mui-tab-label\">消息</span>";
				} else {
					parent.innerHTML = "<span class=\"mui-icon ion-ios-chatbubble-outline\"></span><span class=\"mui-tab-label\">消息</span>";
				}
			}
		}
	});
	setTimeout(function() {
		checkMsg(id);
	}, 10000);
}

if (document.getElementById("a_msg")) {
	checkMsg("a_msg");
}
if (document.getElementById("message")) {
	checkMsg("message");
}