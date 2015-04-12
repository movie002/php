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

function baidutongji()
{
	// 百度统计
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?b3e266da506226dbaa921d5d4e1e69b2";
		//var s = document.getElementsByTagName("script")[0]; 
		//s.parentNode.insertBefore(hm, s);
		(document.getElementsByTagName('body')[0]
		|| document.getElementsByTagName('head')[0]).appendChild(hm);	  
	})();
}

function cnzz()
{
	(function() {
		var cnzz = document.createElement('script');
		cnzz.type = 'text/javascript';
		cnzz.src = 'http://s22.cnzz.com/z_stat.php?id=1253404551&web_id=1253404551';
		(document.getElementsByTagName('body')[0]
		||document.getElementsByTagName('head')[0]).appendChild(cnzz);
	})();
}

function dhsay()
{
	//延时调用 dh_say.php 节省时间
	var iframe_say = document.getElementById('iframe_say');
	iframe_say.innerHTML = '<iframe id="iframe_say" allowtransparency="true" width="95%" height="16px" src="http://s.yfsoso.com/dh_say.php" frameBorder="0" scrolling="no" ></iframe>';
}

function search()
{
    openwin("http://s.yfsoso.com/s.php?q="+f1.submittext.value,"mspg9");
}

function openwin(url,id)
{
	var a = document.createElement("a");
	a.setAttribute("href", url);
	a.setAttribute("target", "_blank");
	a.setAttribute("id", id);
	document.body.appendChild(a);
	a.click();
}

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
