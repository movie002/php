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

//光标聚焦
window.onload = function ()
{
	// 公共的函数
	document.getElementById('submittext').focus();
	startTime();
	loadimg();
	showImgs();
	dhsay();
	// iframe chrome 加载会跳动，这里在最后加载，避免跳动
	//document.getElementById('weather').innerHTML='<iframe allowtransparency="true" frameborder="0" width="180" height="36" scrolling="no" src="http://tianqi.2345.com/plugin/widget/index.htm?s=3&z=3&t=1&v=0&d=3&k=&f=1&q=1&e=1&a=1&c=54511&w=180&h=36"></iframe>';
	// 加载结束之后再调用百度统计代码，要不然会频繁刷新
	baidutongji();
	
	//page独有的函数	
	//百度分享
	window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":["mshare"],"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{"bdSize":16}};with(document)0[(body||getElementsByTagName('head')[0]).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
	google();
	duoshuo();
};