/**
 * 文本框根据输入内容自适应高度
 * @param                {HTMLElement}        输入框元素
 * @param                {Number}                设置光标与输入框保持的距离(默认0)
 * @param                {Number}                设置最大高度(可选)
 */
var autoTextarea = function (elem, extra, maxHeight) {
	extra = extra || 0;
	var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
		isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
		addEvent = function (type, callback) {
			elem.addEventListener ?
				elem.addEventListener(type, callback, false) :
				elem.attachEvent('on' + type, callback);
		},
		getStyle = elem.currentStyle ? function (name) {
			var val = elem.currentStyle[name];

			if (name === 'height' && val.search(/px/i) !== 1) {
				var rect = elem.getBoundingClientRect();
				return rect.bottom - rect.top -
					parseFloat(getStyle('paddingTop')) -
					parseFloat(getStyle('paddingBottom')) + 'px';        
			};

			return val;
		} : function (name) {
			return getComputedStyle(elem, null)[name];
		},
			minHeight = parseFloat(getStyle('height'));

		elem.style.resize = 'none';

		var change = function () {
			var scrollTop, height,
				padding = 0,
				style = elem.style;

			if (elem._length === elem.value.length) return;
			elem._length = elem.value.length;

			if (!isFirefox && !isOpera) {
				padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
			};
			scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

			elem.style.height = minHeight + 'px';
			if (elem.scrollHeight > minHeight) {
				if (maxHeight && elem.scrollHeight > maxHeight) {
					height = maxHeight - padding;
					style.overflowY = 'auto';
				} else {
					height = elem.scrollHeight - padding;
					style.overflowY = 'hidden';
				};
				style.height = height + extra + 'px';
				scrollTop += parseInt(style.height) - elem.currHeight;
				document.body.scrollTop = scrollTop;
				document.documentElement.scrollTop = scrollTop;
				elem.currHeight = parseInt(style.height);
			};
		};

		addEvent('propertychange', change);
		addEvent('input', change);
		addEvent('focus', change);
		change();
};





function jumpUrl(url) {
	window.location.href = '/analyze/report/' + url + '.php';

}

function getId(x) {
	return document.getElementById(x);
}
//tab 切换
function change(x) {

	for (i = 1; i < 3; i++) {
		getId("report" + i).style.display = "none";
		getId("c" + i).className = "nwidth"
	}

	if(2 == x){
		getId("report1_wrapper").style.display = "none"
	} else if(1 == x){
		getId("report1_wrapper").style.display = "block";
	}

	getId("report" + x).style.display = "block";
	getId("c" + x).className = "ta";


}

function showMenu(id,o){
	var x = document.getElementById(id);
	if(x.style.display =="")
		CookieMenu.remove(id);
	else
		CookieMenu.add(id);
	//o.title == "折叠" ? o.title = "展开" : o.title = "折叠";
	o.className == "op_3" ? o.className = "op_2" : o.className = "op_3";
	x.style.display = x.style.display == "" ? "none" : "";


}
function showHidden(x,o){
	var x = document.getElementById(x);
	x.style.display = x.style.display == "" ? "none" : "";
	x.style.display==""?o.className ="op_3":o.className = "op_2";
}


//for record click by geda@ 2009-11-24 
var CookieMenu = {
get: function () {
		 var ret = [], c = getCookie('_clickid') || '',
		 takens = c.split(':');
		 if (!c) return null;
		 for (var i = 0; i < takens.length; i ++) {
			 ret.push({
name: unescape(takens[i])
});
}
return ret;
},
remove: function (name) {
			var c = (getCookie('_clickid') || '').
				replace(new RegExp('(^|:)' + name + ':[^:]+'), '');
			if (c.charAt(0) == ':')
				c = c.substring(1, c.length);
			//alert(c);
			setCookie('_clickid', c);
		},
add: function (name) {
		 if (!name) return;
		 var menus = this.get(), exists = 0;
		 if (menus) {
			 for (var i = 0; i < menus.length; i ++)
				 if (menus[i].name == name) {
					 //alert(menus[i].name);
					 exists = true;
					 break;
				 }
		 }
		 if (exists) return;
		 var c = getCookie('_clickid');
		 c = (c || '') + (c ? ':' : '')  + escape(name);
		 setCookie('_clickid', c);
	 },
show:function(){
		 var c = getCookie('_clickid') || '',plus,
		 takens = c.split(':');
		 //alert(c);
		 if (!c) return null;
		 for (var i = 0; i < takens.length; i ++) {
			 if(document.getElementById(takens[i]))
				 document.getElementById(takens[i]).style.display='';
			 //alert(takens[i]);
			 if(typeof(takens[i])=='string'){
				 if(document.getElementById(takens[i])){
					 plus =takens[i]+'plus';
					 document.getElementById(plus).className='op_3';
				 }
			 }
		 }
	 }
};
function getCookie (name) {
	var nameEQ = name + '=';
	var ca = document.cookie.replace(/\s/g, '').split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		if (c.indexOf(nameEQ) == 0){
			return unescape(c.substring(nameEQ.length, c.length).replace(/\+/g, ' '));
		}
	}
	return null;
}
function setCookie (cookieName, cookieValue, nDays) {
	var today = new Date;
	var expire = new Date;
	if (nDays) {
		expire.setTime(today.getTime() + 3600000 * 24 * nDays);
	}
	document.cookie = cookieName + '=' + escape(cookieValue) +
		(nDays ? ('; expires=' + expire.toGMTString()) : '') + '; path=/';
}
/* 关闭与展开*/
jQuery(function(){
		jQuery('#tipClosed').click(function(){
			jQuery('#tips').toggle();
			if(jQuery('#tipClosed').html()=='+'){
			jQuery('#tipClosed').html('×');
			}else{
			jQuery('#tipClosed').html('+');
			}
			});
		jQuery("#tipClosed").hover(function(){
			jQuery(this).css({
color:'red',
cursor:"pointer"
});
			},function(){
			jQuery(this).css(
				{
color:'#000'
});
			});
		});
jQuery(document).ready(function(){
		jQuery(".stripe_tb tr").mouseover(
			function(){jQuery(this).addClass("over");}).mouseout(
			function(){jQuery(this).removeClass("over");}
			)
		jQuery(".stripe_tb tr:even").addClass("alt"); 
		});
function changeColor(){
	jQuery(".stripe_tb tr").mouseover(
			function(){jQuery(this).addClass("over");}).mouseout(
			function(){jQuery(this).removeClass("over");}
			)
			jQuery(".stripe_tb tr:even").addClass("alt"); 

}
jQuery(document).ready(function(){CookieMenu.show()});
