if(!window.console || !console.firebug)
{
    var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
    "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

    window.console = {};
    for (var i = 0; i < names.length; ++i)
        window.console[names[i]] = function() {};
}
function nextPage(nextPage)
{
	var pages=document.getElementById(nextPage.pagesMain);//分页内容
	var pagesNav=document.getElementById(nextPage.pagesNav);//分页数
	var prev=document.getElementById(nextPage.prevBtn);//上一页
	var next=document.getElementById(nextPage.nextBtn);//下一页
	var curNum=nextPage.curNum;//当前页
	var leng=Math.ceil(pages.children.length/curNum); //页数
	var pageList="";//自动生成页码 数组
	var num=0;//当前的index
	//自动生成页码li
	for(var i=0;i<leng;i++)
	{
		pageList+="\<li\>"+parseInt(i+1)+"\<\/li\>";
	};
	//给ul加上 页码
	pagesNav.innerHTML=pageList;
	//默认第一页 给第一页页码加上高亮
	pagesNav.children[0].className=nextPage.activeClass;

	//给点击页码加上 高亮class 返回index
	for(var i=0;i<pagesNav.children.length;i++)
	{
		pagesNav.children[i].index=i;
		pagesNav.children[i].onclick=function()
		{
			for(var i=0;i<pagesNav.children.length;i++)
			{
				pagesNav.children[i].className="";                                
			}
			this.className=nextPage.activeClass;
			num=this.index;
			ini(num);// 传入当前页的index 用ini方法显示点击到的组
		}
	}
	
	//上一页
	prev.onclick=function()
	{
		if (num==0)
			return false;
		else
		{
			console.log(num);
			for(var i=0;i<pagesNav.children.length;i++)
				pagesNav.children[i].className="";                                
			pagesNav.children[num-1].className=nextPage.activeClass;
			ini(num-1);
			return num--;
		}
	}
	
	//下一页
	next.onclick=function()
	{
		if (num==leng-1) 
			return false;
		else
		{
			console.log(leng);
			for(var i=0;i<pagesNav.children.length;i++)
				pagesNav.children[i].className="";                                
			pagesNav.children[num+1].className=nextPage.activeClass;
			ini(num+1);
			return num++;
		}
	}
	
	//初始化显示第一页内容 隐藏后面
	function ini(iniBum)
	{
		var iniBum=iniBum;
		for(var j=0;j<leng;j++)
		{
			for(var i=0;i<curNum;i++)
			{
				if(pages.children[(j*curNum)+i]==undefined)
					continue;
				pages.children[(j*curNum)+i].style.display="none";
			}
		}
		for(var i=0;i<curNum;i++)
		{
			if(pages.children[(iniBum*curNum)+i]==undefined)
				continue;
			pages.children[(iniBum*curNum)+i].style.display="block";
		}
	}
	
	//初始化显示第一页内容 隐藏后面	
	ini(nextPage.ini);
};