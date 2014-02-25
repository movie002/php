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
	startTime();
	loadimg();
	showImgs();
	document.getElementById('submittext').focus();
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