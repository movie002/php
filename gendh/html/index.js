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
	dhsay();	
};