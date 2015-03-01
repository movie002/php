document.createElement("imgdao");

function SetCookie(name,value)//两个参数，一个是cookie的名子，一个是值
{
	var Days = 30; //此 cookie 将被保存 30 天
	var exp = new Date();    //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days*86400000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ "; path=/";
};
function getCookie(name)//取cookies函数        
{
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	 if(arr != null) return unescape(arr[2]); return null;
};

//解决ie的这个不符合w3c的方法
var style_need='.topnav ul li ,.celeimg_title{filter:alpha(opacity=90);}';
style_need+='.celeimg_title{filter:alpha(opacity=50);}';

var movie002_day =  getCookie("movie002_day");
if(movie002_day==null)
{
	var now = new Date();
	var hour = now.getHours();
	if(hour>=21||hour<=5)
		movie002_day='night';
	else
		movie002_day='day';
}
if(movie002_day=='night')
{
	style_need +='#wrapper{background:#383838;border:1px solid #B6AA7B}';
	style_need +='body{background:#383838;color:#B6AA7B}';
	style_need +='a{color:#B6AA7B;background:transparent}';
	style_need +='a:visited {color:#447FA8;}';
//	style_need +='.order{background:url(%home%images/logonight_8.gif) no-repeat;}';
	style_need +='#service_list{background:#383838;}';
	style_need +='#global_nav{background:#383838;}';
	style_need +='#topics-wrapper{border:none;}';
	style_need +='.moviemeta,.celeimg_name,.colormeta{color:#999}';
	style_need +='.post-topic-area{color:#999}.post-topic-area:hover{color:#bbb}';
	style_need +='.moviedate{color:#D04230}';
	style_need +='.titlecontent,.entry a {color:#bbb}';
	style_need +='.entry h4,.entry li{color:#B6AA7B;background:none}';
	style_need +='#movie002_day_2{font-weight:700}';
	style_need +='#movie002_day_1{font-weight:normal}';
	style_need +='#article_index{background:none}';
	style_need +='.searchinput,.searchinput:focus{background:#383838;color:#B6AA7B;}';
	style_need +='.topnav li a, .topnav li a:link, .topnav li a:visited{color:#B6AA7B;}';
	style_need +='.topnav li li a, .topnav li li a:link, .topnav li li a:visited,.topnav li a:hover,.topnav li a:active{background:#383838;}';
	style_need +='.topnav li a:hover,.topnav li li a:hover{background:#52686F;color:#fff}';
	style_need +='.listall,.sidebar_content ul li,.topic_list ol li,.col2,.col3,.col4,.col5,.col6,.bb{border-bottom:1px solid #888}';
	style_need +='.br{border-right:1px solid #888}';
	style_need +='#content,#comment,#sidebar,.topics-wrapper{border: 1px solid #888;}';	
}

var movie002_width =  getCookie("movie002_width");
if(movie002_width==null)
{
	if(window.screen.width >=1280)
		movie002_width='1280';
	else
		movie002_width= '1024';
}
if(movie002_width=='1280')
{
	style_need += ' .width_1{width: 1080px;}';
	style_need += ' .width_2{width: 1050px;}';
	style_need += ' .width_3{width: 775px;}';
	style_need +=' #movie002_width_2{font-weight:700}';
	style_need +=' #movie002_width_1{font-weight:normal}';
	style_need +=' a.gotop_btn{margin-left:540px';	
}
if(style_need!=null)
{
	style_need = '<style type="text/css">'+style_need+'</style>';
	//alert(style_need);
	document.write(style_need);
}

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

function hide(id1,id2,tx1)
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

function deleteurl(title,url)
{
	var deltitle = document.getElementById('deltitle');
	deltitle.innerHTML=title;
	var delurl = document.getElementById('delurl');
	delurl.innerHTML=url;
	var delurlinput = document.getElementById('delurlinput');
	delurlinput.value=url;	
	showmiddle('del');
};