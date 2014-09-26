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

//function google()
//{
//	//google adsence
//	(function() {
//		var gs = document.createElement('script');
//		gs.type = 'text/javascript';
//		gs.async = true;
//		gs.src = 'http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
//		(document.getElementsByTagName('body')[0]
//		||document.getElementsByTagName('head')[0]).appendChild(gs);
//	})();	
//	//<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
//}

//function weibo()
//{
//	(function() {
//		var wb = document.createElement('script');
//		wb.type = 'text/javascript';
//		wb.charset = 'UTF-8';
//		wb.src = 'http://tjs.sjs.sinajs.cn/open/api/js/wb.js';
//		(document.getElementsByTagName('body')[0]
//		||document.getElementsByTagName('head')[0]).appendChild(wb);
//	})();
//}

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
	
	//list独有的函数
	duoshuo();
//	google();
//	weibo();
	dhsay();
};