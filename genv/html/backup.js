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

// 制定使用哪个作为搜索
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