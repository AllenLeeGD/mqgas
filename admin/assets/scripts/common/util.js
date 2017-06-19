var edu_host = "http://localhost:8030";
//var edu_host = "http://newoceangas.cn/";
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
		if(/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
		for(var k in o)
			if(new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		return fmt;
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
		for(var i = 0; i < len; i++) {
			var code = str.charCodeAt(i);
			if(code > 0x0000 && code <= 0x007F) {
				// 单字节，这里并不考虑0x0000，因为它是空字节  
				// U+00000000 – U+0000007F  0xxxxxxx  
				res.push(str.charAt(i));
			} else if(code >= 0x0080 && code <= 0x07FF) {
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
			} else if(code >= 0x0800 && code <= 0xFFFF) {
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
			} else if(code >= 0x00010000 && code <= 0x001FFFFF) {
				// 四字节  
				// U+00010000 – U+001FFFFF  11110xxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else if(code >= 0x00200000 && code <= 0x03FFFFFF) {
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
		for(var i = 0; i < len; i++) {
			var code = str.charCodeAt(i);
			// 对第一个字节进行判断  
			if(((code >> 7) & 0xFF) == 0x0) {
				// 单字节  
				// 0xxxxxxx  
				res.push(str.charAt(i));
			} else if(((code >> 5) & 0xFF) == 0x6) {
				// 双字节  
				// 110xxxxx 10xxxxxx  
				var code2 = str.charCodeAt(++i);
				var byte1 = (code & 0x1F) << 6;
				var byte2 = code2 & 0x3F;
				var utf16 = byte1 | byte2;
				res.push(Sting.fromCharCode(utf16));
			} else if(((code >> 4) & 0xFF) == 0xE) {
				// 三字节  
				// 1110xxxx 10xxxxxx 10xxxxxx  
				var code2 = str.charCodeAt(++i);
				var code3 = str.charCodeAt(++i);
				var byte1 = (code << 4) | ((code2 >> 2) & 0x0F);
				var byte2 = ((code2 & 0x03) << 6) | (code3 & 0x3F);
				utf16 = ((byte1 & 0x00FF) << 8) | byte2
				res.push(String.fromCharCode(utf16));
			} else if(((code >> 3) & 0xFF) == 0x1E) {
				// 四字节  
				// 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx  
			} else if(((code >> 2) & 0xFF) == 0x3E) {
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
		if(!str) {
			return '';
		}
		var utf8 = this.UTF16ToUTF8(str); // 转成UTF8  
		var i = 0; // 遍历索引  
		var len = utf8.length;
		var res = [];
		while(i < len) {
			var c1 = utf8.charCodeAt(i++) & 0xFF;
			res.push(this.table[c1 >> 2]);
			// 需要补2个=  
			if(i == len) {
				res.push(this.table[(c1 & 0x3) << 4]);
				res.push('==');
				break;
			}
			var c2 = utf8.charCodeAt(i++);
			// 需要补1个=  
			if(i == len) {
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
		if(!str) {
			return '';
		}

		var len = str.length;
		var i = 0;
		var res = [];

		while(i < len) {
			code1 = this.table.indexOf(str.charAt(i++));
			code2 = this.table.indexOf(str.charAt(i++));
			code3 = this.table.indexOf(str.charAt(i++));
			code4 = this.table.indexOf(str.charAt(i++));

			c1 = (code1 << 2) | (code2 >> 4);
			c2 = ((code2 & 0xF) << 4) | (code3 >> 2);
			c3 = ((code3 & 0x3) << 6) | code4;

			res.push(String.fromCharCode(c1));

			if(code3 != 64) {
				res.push(String.fromCharCode(c2));
			}
			if(code4 != 64) {
				res.push(String.fromCharCode(c3));
			}

		}

		return this.UTF8ToUTF16(res.join(''));
	}
};

function base64_encode(str) {
	var str = toUTF8(str);
	var base64EncodeChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
	var out, i, j, len, r, l, c;
	i = j = 0;
	len = str.length;
	r = len % 3;
	len = len - r;
	l = (len / 3) << 2;
	if(r > 0) {
		l += 4;
	}
	out = new Array(l);

	while(i < len) {
		c = str.charCodeAt(i++) << 16 |
			str.charCodeAt(i++) << 8 |
			str.charCodeAt(i++);
		out[j++] = base64EncodeChars[c >> 18] + base64EncodeChars[c >> 12 & 0x3f] + base64EncodeChars[c >> 6 & 0x3f] + base64EncodeChars[c & 0x3f];
	}
	if(r == 1) {
		c = str.charCodeAt(i++);
		out[j++] = base64EncodeChars[c >> 2] + base64EncodeChars[(c & 0x03) << 4] + "==";
	} else if(r == 2) {
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
	if(len % 4 != 0) {
		return '';
	}
	if(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\+\/\=]/.test(str)) {
		return '';
	}
	if(str.charAt(len - 2) == '=') {
		r = 1;
	} else if(str.charAt(len - 1) == '=') {
		r = 2;
	} else {
		r = 0;
	}
	l = len;
	if(r > 0) {
		l -= 4;
	}
	l = (l >> 2) * 3 + r;
	out = new Array(l);

	i = j = 0;
	while(i < len) {
		// c1
		c1 = base64DecodeChars[str.charCodeAt(i++)];
		if(c1 == -1) break;

		// c2
		c2 = base64DecodeChars[str.charCodeAt(i++)];
		if(c2 == -1) break;

		out[j++] = String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

		// c3
		c3 = base64DecodeChars[str.charCodeAt(i++)];
		if(c3 == -1) break;

		out[j++] = String.fromCharCode(((c2 & 0x0f) << 4) | ((c3 & 0x3c) >> 2));

		// c4
		c4 = base64DecodeChars[str.charCodeAt(i++)];
		if(c4 == -1) break;

		out[j++] = String.fromCharCode(((c3 & 0x03) << 6) | c4);
	}
	return toUTF16(out.join(''));
}

function toUTF8(str) {
	if(str.match(/^[\x00-\x7f]*$/) != null) {
		return str.toString();
	}
	var out, i, j, len, c, c2;
	out = [];
	len = str.length;
	for(i = 0, j = 0; i < len; i++, j++) {
		c = str.charCodeAt(i);
		if(c <= 0x7f) {
			out[j] = str.charAt(i);
		} else if(c <= 0x7ff) {
			out[j] = String.fromCharCode(0xc0 | (c >>> 6),
				0x80 | (c & 0x3f));
		} else if(c < 0xd800 || c > 0xdfff) {
			out[j] = String.fromCharCode(0xe0 | (c >>> 12),
				0x80 | ((c >>> 6) & 0x3f),
				0x80 | (c & 0x3f));
		} else {
			if(++i < len) {
				c2 = str.charCodeAt(i);
				if(c <= 0xdbff && 0xdc00 <= c2 && c2 <= 0xdfff) {
					c = ((c & 0x03ff) << 10 | (c2 & 0x03ff)) + 0x010000;
					if(0x010000 <= c && c <= 0x10ffff) {
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
	if((str.match(/^[\x00-\x7f]*$/) != null) ||
		(str.match(/^[\x00-\xff]*$/) == null)) {
		return str.toString();
	}
	var out, i, j, len, c, c2, c3, c4, s;

	out = [];
	len = str.length;
	i = j = 0;
	while(i < len) {
		c = str.charCodeAt(i++);
		switch(c >> 4) {
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
				switch(c & 0xf) {
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
						if(0 <= s && s <= 0xfffff) {
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
	for(j = 0; j <= 3; j++)
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
	for(i = 0; i < nblk * 16; i++) blks[i] = 0;
	for(i = 0; i < str.length; i++)
		blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
	blks[i >> 2] |= 0x80 << ((i % 4) * 8);
	blks[nblk * 16 - 2] = str.length * 8;
	return blks;
}

function isEmail(str) {
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	return reg.test(str);
}

function isMobile(str) {
	var reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
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

/* 
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function add(x, y) {
	var lsw = (x & 0xFFFF) + (y & 0xFFFF);
	var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
	return(msw << 16) | (lsw & 0xFFFF);
}

/* 
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt) {
	return(num << cnt) | (num >>> (32 - cnt));
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

	for(i = 0; i < x.length; i += 16) {
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

function center(obj) {
	var screenWidth = $(window).width();
	var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
	var scrolltop = $(document).scrollTop(); //获取当前窗口距离页面顶部高度   
	var objLeft = (screenWidth - obj.width()) / 2;
	var objTop = (screenHeight - obj.height()) / 2 + scrolltop;
	obj.css({
		"position": "absolute",
		"top": objTop,
		"left": objLeft
	});
}
/**
eshop工具类
**/
var Util = function() {

	//保留小数点后两位
	this.changeTwoDecimal = function changeTwoDecimal(floatvar) {
		var f_x = parseFloat(floatvar);
		if(isNaN(f_x)) {
			alert('function:changeTwoDecimal->parameter error');
			return false;
		}
		var f_x = Math.round(floatvar * 100) / 100;
		return f_x;
	}

	this.getParam = function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");

		var r = window.location.search.substr(1).match(reg);

		if(r != null) return unescape(r[2]);

		return null;
	}
	this.checkchk = function(obj) {
		if($(obj).prop('checked') == true) {
			$(obj).parent('span').addClass('checked');
		} else {
			$(obj).parent('span').removeClass('checked');
		}
	}
	this.showLoading = function() {
		$('#div_loader').show();
		center($('#div_loader'));
		//浏览器窗口大小改变时   
		$(window).resize(function() {
			center($('#div_loader'));
		});
		//浏览器有滚动条时的操作、   
		$(window).scroll(function() {
			center($('#div_loader'));
		});
	}
	this.hideLoading = function() {
		$('#div_loader').hide();
	}
	this.getCustPicFolder = function() {
		return "../../Upload/";
	}
	this.getPicFolder = function() {
		return "../Upload/";
	}
	this.checkradio = function(obj) {
		var name = $(obj).attr('name');
		var alList = $("input[name='" + name + "']");
		alList.each(function() {
			$(this).parent('span').removeClass('checked');
			$(this).prop('checked', false);
		});
		$(obj).prop('checked', true);
		$(obj).parent('span').addClass('checked');
	}
	this.postUrl = function(url, success, data, error) {
		/*if (error == undefined || error == null) {
			error = function() {
				alert('内部服务器错误，请联系管理员');
			}
		}*/
		$.ajax({
			type: 'POST',
			url: edu_host + "/index.php" + url,
			success: success,
			data: data,
			error: error
		});
	}
	this.postCustUrl = function(url, success, data, error) {
		/*if (error == undefined || error == null) {
			error = function() {
				alert('内部服务器错误，请联系管理员');
			}
		}*/
		$.ajax({
			type: 'POST',
			url: "/App/index.php" + url,
			success: success,
			data: data,
			error: error
		});
	}
	this.goCust500 = function(msg) {
		window.location.href = "pagerror.html";
	}
	this.getCommentType = function(type) {
		if(type == 0) {
			return "<div style=\"color:#ff0000;text-align:center;\" title=\"好评\"><i class=\"fa fa-thumbs-o-up\"></i></div>";
		} else if(type == 1) {
			return "<div style=\"color:#ffcc00;text-align:center;\" title=\"中评\"><i class=\"fa fa-hand-o-right\"></i></div>";
		} else if(type == 2) {
			return "<div style=\"text-align:center;\" title=\"差评\"><i class=\"fa fa-thumbs-o-down\"></i></div>";
		}
	}
	this.getUrl = function(url, success, error) {
		$.ajax({
			type: 'POST',
			url: edu_host + "/index.php" + url,
			success: success,
			error: error
		});
	}

	this.getCustUrl = function(url, success, error) {
		$.ajax({
			type: 'POST',
			url: "../native/platform.php?code=" + this.encode(url),
			success: success,
			error: error
		});
	}

	this.postUrlWithSessionCheck = function(url, success) {
		//检查session
		this.postUrl(url, success);
	}

	this.encode = function(data) {
		return base64_encode(encodeURI(data));
	}

	this.successMsg = function(msg, time) {
		if(time == undefined || time == null) {
			time = 3000;
		}
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-top-right",
			"onclick": null,
			"showDuration": "200",
			"hideDuration": "1000",
			"timeOut": time,
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		toastr.success(msg);
	}

	this.errorMsg = function(msg, time) {
		if(time == undefined || time == null) {
			time = 3000;
		}
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-top-right",
			"onclick": null,
			"showDuration": "200",
			"hideDuration": "1000",
			"timeOut": time,
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		toastr.error(msg);
	}

	this.strencode = function(string, encrypt) {
		key = calcMD5(encrypt);
		string = Base64.decode(string);
		len = key.length;
		code = '';
		for(i = 0; i < string.length; i++) {
			k = i % len;
			code += String.fromCharCode(string.charCodeAt(i) ^ key.charCodeAt(k));
		}
		return Base64.decode(code);
	}

	this.isNullStr = function(str) {
		if(str == null) {
			return true;
		} else if(str == "") {
			return true;
		} else if(str == undefined) {
			return true;
		} else if(str == "null") {
			return true;
		} else {
			return false;
		}
	}
};