function nTabs(thisObj,Num)
{
	if(thisObj.className == "active")return;
	var tabObj = thisObj.parentNode.id;
	var tabList = document.getElementById(tabObj).getElementsByTagName("li");
	for(i=0; i <tabList.length; i++)
	{
	  if (i == Num)
	  {
		thisObj.className = "active"; 
		document.getElementById(tabObj+"_Content"+i).style.display = "block";
	  }
	  else
	  {
		tabList[i].className = "normal"; 
		document.getElementById(tabObj+"_Content"+i).style.display = "none";
	  }
	} 
};

function showhide(id1,id2,tx1,tx2)
{
	var id1div = document.getElementById(id1);
	var id2div = document.getElementById(id2);
	if(id2div.style.display=='block')
	{
		id2div.style.display='none';
		id1div.innerHTML=tx1;
	}
	else if(id2div.style.display=='none')
	{
		id2div.style.display='block';
		id1div.innerHTML=tx2;
	}	
};

function hide(id1,id2,tx1,idjump)
{
	var id1div = document.getElementById(id1);
	var id2div = document.getElementById(id2);
	if(id2div.style.display=='block')
	{
		id2div.style.display='none';
		id1div.innerHTML=tx1;	
		//scroller(idjump, 800);
		//window.location.hash=idjump;
		document.getElementById(idjump).scrollIntoView();
	}
};

function hide2(id1,id2,tx1)
{
	var id1div = document.getElementById(id1);
	var id2div = document.getElementById(id2);
	if(id2div.style.display=='block')
	{
		id2div.style.display='none';
		id1div.innerHTML=tx1;
	}
};

function showmiddle(id)
{
	var id = document.getElementById(id);
	id.style.display='block';
};

function hidemiddle(id)
{
	var id = document.getElementById(id);
	id.style.display='none';
};

function deleteurl(title,url,id)
{
	var deltitle = document.getElementById('deltitle');
	deltitle.innerHTML=title;
	var delurl = document.getElementById('delurl');
	delurl.innerHTML=url;
	var delurlinput = document.getElementById('delurlinput');
	delurlinput.value=url;	
	showmiddle('del');
};

function duoshuo()
{
	//多说
	(function() {
		var ds = document.createElement('script');
		ds.type = 'text/javascript';
		ds.async = false;
		ds.src = 'http://static.duoshuo.com/embed.js';
		ds.charset = 'UTF-8';
		(document.getElementsByTagName('body')[0]
		|| document.getElementsByTagName('head')[0]).appendChild(ds);
	})();
}

function google()
{
	//google adsence
	(function() {
		var gs = document.createElement('script');
		gs.type = 'text/javascript';
		gs.async = true;
		gs.src = 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
		(document.getElementsByTagName('body')[0]
		||document.getElementsByTagName('head')[0]).appendChild(gs);
	})();	
	//<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
}

function weibo()
{
	(function() {
		var wb = document.createElement('script');
		wb.type = 'text/javascript';
		wb.charset = 'UTF-8';
		wb.src = 'http://tjs.sjs.sinajs.cn/open/api/js/wb.js';
		(document.getElementsByTagName('body')[0]
		||document.getElementsByTagName('head')[0]).appendChild(wb);
	})();
}

//光标聚焦
window.onload = function ()
{
	// 公共的函数
	document.getElementById('submittext').focus();
	startTime();
	loadimg();
	showImgs();
	// iframe chrome 加载会跳动，这里在最后加载，避免跳动
	//document.getElementById('weather').innerHTML='<iframe allowtransparency="true" frameborder="0" width="180" height="36" scrolling="no" src="http://tianqi.2345.com/plugin/widget/index.htm?s=3&z=3&t=1&v=0&d=3&k=&f=1&q=1&e=1&a=1&c=54511&w=180&h=36"></iframe>';
	// 加载结束之后再调用百度统计代码，要不然会频繁刷新
	cnzz();
	
	//page独有的函数	
	//百度分享
	window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":["mshare"],"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{"bdSize":16}};with(document)0[(body||getElementsByTagName('head')[0]).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
	google();
	duoshuo();
	weibo();
	dhsay();	
};