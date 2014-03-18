// 说明 ：用 Javascript 实现锚点(Anchor)间平滑跳转
// 来源 ：ThickBox 2.1
// 整理 ：Yanfu Xie [xieyanfu@yahoo.com.cn]
// 网址 ：http://www.codebit.cn
// 日期 ：07.01.17
  
// 转换为数字
function intval(v)
{
    v = parseInt(v);
    return isNaN(v) ? 0 : v;
}
  
// 获取元素信息
function getPos(e)
{
    var l = 0;
    var t  = 0;
    var w = intval(e.style.width);
    var h = intval(e.style.height);
    var wb = e.offsetWidth;
    var hb = e.offsetHeight;
    while (e.offsetParent){
        l += e.offsetLeft + (e.currentStyle?intval(e.currentStyle.borderLeftWidth):0);
        t += e.offsetTop  + (e.currentStyle?intval(e.currentStyle.borderTopWidth):0);
        e = e.offsetParent;
    }
    l += e.offsetLeft + (e.currentStyle?intval(e.currentStyle.borderLeftWidth):0);
    t  += e.offsetTop  + (e.currentStyle?intval(e.currentStyle.borderTopWidth):0);
    return {x:l, y:t, w:w, h:h, wb:wb, hb:hb};
}
  
// 获取滚动条信息
function getScroll() 
{
    var t, l, w, h;
     
    if (document.documentElement && document.documentElement.scrollTop) {
        t = document.documentElement.scrollTop;
        l = document.documentElement.scrollLeft;
        w = document.documentElement.scrollWidth;
        h = document.documentElement.scrollHeight;
    } else if (document.body) {
        t = document.body.scrollTop;
        l = document.body.scrollLeft;
        w = document.body.scrollWidth;
        h = document.body.scrollHeight;
    }
    return { t: t, l: l, w: w, h: h };
}
  
// 锚点(Anchor)间平滑跳转
function scroller(el, duration)
{
    if(typeof el != 'object') { el = document.getElementById(el); }
  
    if(!el) return;
  
    var z = this;
    z.el = el;
    z.p = getPos(el);
    z.s = getScroll();
    z.clear = function(){window.clearInterval(z.timer);z.timer=null};
    z.t=(new Date).getTime();
  
    z.step = function(){
        var t = (new Date).getTime();
        var p = (t - z.t) / duration;
        if (t >= duration + z.t) {
            z.clear();
            window.setTimeout(function(){z.scroll(z.p.y, z.p.x)},13);
        } else {
            st = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.y-z.s.t) + z.s.t;
            sl = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.x-z.s.l) + z.s.l;
            z.scroll(st, sl);
        }
    };
    z.scroll = function (t, l){window.scrollTo(l, t)};
    z.timer = window.setInterval(function(){z.step();},13);
}

// end：ThickBox

function f(i)  
{   
	var url='';
	var j= document.getElementsByTagName('input');
	if(i==0)
	{
		j[0].disabled=false;
		j[1].disabled=true;
		j[2].disabled=true;
		j[3].disabled=true;
		j[4].disabled=true;
		j[5].disabled=true;					
		url="%home%cse.html";
	}
	else
	{											
		j[1].value=j[0].value;
		j[0].disabled=true;
		j[1].disabled=false;
		j[2].disabled=false;
		j[3].disabled=false;
		j[4].disabled=false;
		j[5].disabled=false;
		url="http://www.baidu.com/baidu";
	}
	document.f1.action = url;
	document.f1.submit();
	return true;					
};
function startTime()
{
	var today=new Date();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	// add a zero in front of numbers<10
	m=checkTime(m);
	s=checkTime(s);
	document.getElementById('txt').innerHTML=h+":"+m+":"+s;
	t=setTimeout('startTime()',500);
};
function checkTime(i)
{
	if (i<10) 
	  {i="0" + i;}
	  return i;
};
function loadimg()
{
	var imgTags = document.getElementsByTagName('img');	
	for (var i=0;i<imgTags.length;i++)//数组中的每一个变量
	{
		var imgSrc = imgTags[i].getAttribute('data-src');
		if(imgSrc!=null)
			imgTags[i].setAttribute('src',imgSrc);
	}
};
document.createElement("imgdao");
function showImgs()
{
	var imgTags = document.getElementsByTagName('imgdao');
	for (var i=0;i<imgTags.length;i++)//数组中的每一个变量
	{
		var link_src = imgTags[i].getAttribute('link_src');
		var img_src = imgTags[i].getAttribute('img_src');
		var src_width = imgTags[i].getAttribute('src_width');
		var src_height = imgTags[i].getAttribute('src_height');
		var frm_height = src_height;
		var alt = imgTags[i].getAttribute('alt');

		var name = imgTags[i].getAttribute('name');
		if(name!=null)
		{
			frm_height = imgTags[i].getAttribute('frm_height');
			window['img'+i]='<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body leftmargin="0" topmargin="0" style="background-color:transparent"><a href="'+link_src+'" target="_blank"><img style="border:none;width:'+src_width+';height:'+src_height+';" src="'+img_src+'" alt="'+alt+'的图片" rel="nofollow"/></a><div style="font-size:12px;text-align:center;">'+name+'</div></body></html>';
		}
		else
		{
			window['img'+i]='<body leftmargin="0" topmargin="0"><a href="'+link_src+'" target="_blank"><img style="border:none;width:'+src_width+';height:'+src_height+';" src="'+img_src+'" alt="'+alt+'的图片" rel="nofollow"/></a></body>';
		}
		
		var clilddiv=imgTags[i].firstChild;				
		clilddiv.innerHTML = '<iframe allowtransparency="true" src="javascript:parent[\'img'+i+'\'];" frameBorder="0" scrolling="no" width="'+src_width+'" height="'+frm_height+'"></iframe>';
	}
	// iframe chrome 加载会跳动，这里在最后加载，避免跳动
	//document.getElementById('weather').innerHTML='<iframe allowtransparency="true" frameborder="0" width="180" height="36" scrolling="no" src="http://tianqi.2345.com/plugin/widget/index.htm?s=3&z=3&t=1&v=0&d=3&k=&f=1&q=1&e=1&a=1&c=54511&w=180&h=36"></iframe>';
};
		                     
//收藏本站
function AddFavorite(title, url) {
    try {
        window.external.addFavorite(url, title);
    }
    catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
};



//光标聚焦
window.onload = function ()
{
	document.getElementById('submittext').focus();
	startTime();
	loadimg();
	showImgs();
	//延时调用 dh_say.php 节省时间
	var iframe_say = document.getElementById('iframe_say');
	iframe_say.innerHTML = '<iframe id="iframe_say" allowtransparency="true" width="90%" height="16px" src="%home%dh_say.php" frameBorder="0" scrolling="no" ></iframe>';
	
	// 加载结束之后再调用百度统计代码，要不然会频繁刷新
	// 百度统计
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?b3e266da506226dbaa921d5d4e1e69b2";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();	
	//多说
	var duoshuoQuery = {short_name:"movie002"};
	(function() {
		var ds = document.createElement('script');
		ds.type = 'text/javascript';ds.async = true;
		ds.src = 'http://static.duoshuo.com/embed.js';
		ds.charset = 'UTF-8';
		(document.getElementsByTagName('head')[0] 
		|| document.getElementsByTagName('body')[0]).appendChild(ds);
	})();
	//百度分享
	window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":["mshare"],"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{"bdSize":16}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
	//google adsence
	(function() {
		var gs = document.createElement('script');
		gs.type = 'text/javascript';
		gs.async = true;
		gs.src = 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
		(document.getElementsByTagName('head')[0] 
		|| document.getElementsByTagName('body')[0]).appendChild(gs);
	})();	
	//<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
};

window.onscroll = function()
{
	var h =document.body.scrollTop;
	if(!h)
		h=document.documentElement.scrollTop;
	var top = document.getElementById('goTopButton');
	if(h>0)
	{
		top.style.display = 'block';
	}
	else
	{
		top.style.display = 'none';
	}
};