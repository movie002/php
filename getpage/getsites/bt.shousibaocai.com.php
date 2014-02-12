<?php 
/* 使用示例 */   

$sitename=array('qiyi'=>'爱奇艺','sohu'=>'搜狐视频','pptv'=>'PPTV','taomi'=>'淘米','youku'=>'优酷','tudou'=>'土豆','pps'=>'PPS','leshi'=>'乐视','xunlei'=>'迅雷看看','fengxing'=>'风行','huashu'=>'华数TV','56com'=>'56网','m1905'=>'电影网','sina'=>'新浪视频','baomihua'=>'爆米花','ifeng'=>'凤凰视频','cntv'=>'CNTV');


//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../curl.php");
//require("../../common.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//$douban_result = new MovieResult();
//$douban_result->title='猩猩王';
//#$douban_result->aka='名侦探柯南/铁甲奇侠/Iron Man';
//$douban_result->type=1;
//$douban_result->updatetime='2000-05-05 00:00:00';
//get_v360($douban_result);
////gettv($douban_result,'http://v.360.cn/m/hqjpY0n7S0b4TB.html',$douban_result->title,1);
//print_r($douban_result);
//mysql_close($conn);

//处理电影名  
function get_v360(&$resultlast,$pageid=-1)
{ 
	echo " \n begin to get from v360:\n";
	//如果有pageid，说明极有可能360搜索是有记录的，此时只要添加非电影的链接即可
	if($pageid!=-1)
	{
		$sql="select link from link where pageid = $pageid and author='360视频搜索'";
		$sqlresult=dh_mysql_query($sql);
		$count = mysql_fetch_array($sqlresult);
		if(!empty($count))
		{
			echo "有记录，直接update tv ".$count[0]." \n";
			//gettv($resultlast,$count[0],$pageid);
			getmovie($resultlast,$count[0],$resultlast->title,$pageid);
			gettv($resultlast,$count[0],$resultlast->title,$pageid);
			return;
		}
	}
	
	$name = rawurlencode($resultlast->title);   
	$buffer = get_file_curl('http://so.v.360.cn/index.php?kw='.$name);
	echo $buffer;

	if(false==$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
		return;
	}
	preg_match_all('/<a href="([^>]+)" t[^>]+>立即观看&gt;&gt;</s',$buffer,$match);
	print_r($match);
	//return;
	foreach($match[0] as $key=>$each)
	{
		//如果类型不对，直接退出
		//电影
		$thistype=0;
		if(strstr($match[1][$key],"http://v.360.cn/m/"))
			$thistype=1;
		if(strstr($match[1][$key],"http://v.360.cn/tv/"))
			$thistype=2;
		if(strstr($match[1][$key],"http://v.360.cn/ct/"))
			$thistype=3;
		if(strstr($match[1][$key],"http://v.360.cn/dongman/"))
			$thistype=4;
			
		if($resultlast->type!=0 && $thistype!=$resultlast->type)
			continue;
		//找到名字 比对电影名
		$link=str_replace("/","\/",$match[1][$key]);
		preg_match('/<h3><a href="'.$link.'" target="_blank"[\s]+data\-id="[0-9]+" data\-catid="[0-9]+" data\-pos="[0-9]+" data\-btype="title">(.*?)<\/a><span class="category">[^>]+<\/span><\/h3>/s',$buffer,$match1);
		
		//print_r($match1);
		if(empty($match1[1]))
			return;
		$titlesearch=str_replace("<b>","",$match1[1]);
		$titlesearch=str_replace("</b>","",$titlesearch);
		
		if($titlesearch!=$resultlast->title)
		{
			$akas =explode('/',$resultlast->aka);
			//print_r($akas);			
			if (!(array_search($titlesearch,$akas)==false))
			{	
				continue;
			}
		}
		//先插入360的链接
		$url360=$match[1][$key];
		
		if($pageid!=-1)
			update360linkpageid($resultlast,'360视频搜索',$url360,'《'.$titlesearch.'》的视频搜索',$pageid);
		else
			update360link($resultlast,'360视频搜索',$url360,'《'.$titlesearch.'》的360视频搜索结果');
		
		//echo $url360."--> v360 -->".$titlesearch."\n";
		
		if($thistype==1)
			getmovie($resultlast,$url360,$titlesearch,$pageid);
		else
			gettv($resultlast,$url360,$titlesearch,$pageid);
		//找到一个正确的就退出了
		return;
	}
}


function getmovie(&$resultlast,$url,$titlesearch,$pageid)
{
	if(!strstr($url,"http://v.360.cn/m/"))
	{
		echo "get movie can not get !movie url"."$url \n";
		return;
	}
	global $sitename,$sitenick,$sitemeta;
	$buffer = get_file_curl($url);
	//echo $buffer;
	
	preg_match('/testSpeed.test(\(.*?)\)/s',$buffer,$match);
	//print_r($match);
	if(empty($match[1]))
	{
		//有可能只有一个资源
		preg_match('/<li data\-speed="slow"><a playtype="dianying" payMovie="[0-9]+" sitename="(.*?)" needrecord="record" movieid="[0-9]+" class=".*?" href="(.*?)">.*?<\/a><\/li>/s',$buffer,$match);
		//print_r($match);
		if(empty($match[1]))
			return;
			
		$author=$match[1];
		$link = $match[2];
		if($pageid!=-1)
			update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放',$pageid);
		else
			update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放');		
		//echo $link."-->".$author."-->".$sitename[$author].$titlesearch."\n";
			
		return;
	}
		
	preg_match_all('/\{(.*?)\}/s',$match[1],$match1);
		//print_r($match1);
	if(empty($match1[1]))
		return;
	
	foreach($match1[1] as $key=>$eachsite)
	{
		preg_match_all('/"site":"(.*?)".*?"link":"(.*?)"/s',$eachsite,$match2);
		//print_r($match2);
		//echo str_replace("\\\/","\/",$match2[2]);
		$author=$match2[1][0];
		$link = str_replace("\/","/",$match2[2][0]);
		if($pageid!=-1)
			update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放',$pageid);
		else
			update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放');		
	//	echo $link."-->".$author."-->".$sitename[$author].$titlesearch."\n";
	}
}

function gettv(&$resultlast,$url,$titlesearch,$pageid)
{
	if(strstr($url,"http://v.360.cn/m/"))
	{
		echo "get tv can not get movie url"."$url \n";
		return;
	}
	global $sitename,$sitenick,$sitemeta;
	$buffer = get_file_curl($url);
	//echo $buffer;
	preg_match_all('/<a.*?sitename="(.*?)".*?href="(.*?)">(.*?)<\/a>/s',$buffer,$match);
	//print_r($match);
	if(empty($match[1]))
		return;	
	$nameold='';
	foreach($match[1] as $key=>$eachsite)
	{
		$namenew = $match[1][$key];	
		if($nameold!='' && ($namenew != $nameold))
		{
			if($pageid!=-1)
				update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放(更新至'.$title.')',$pageid);
			else
				update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放(更新至'.$title.')');
			//echo $link."-->".$author."-->".$title."-->".$sitename[$author].$titlesearch."\n";	
		}
		$nameold = $namenew;
		$link = $match[2][$key];
		$author = $match[1][$key];
		$title = $match[3][$key];
	}
	if($pageid!=-1)
		update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放(更新至'.$title.')',$pageid);
	else
		update360link($resultlast,$sitename[$author],$link,'《'.$titlesearch.'》'.$sitename[$author].'在线播放(更新至'.$title.')');
	//echo $link."-->".$author."-->".$title."-->".$sitename[$author].$titlesearch."\n";
	return;
}

function update360link($resultlast,$author,$url,$title)
{
	$updatetime=date("Y-m-d  H:i:s");
	$sql="insert into link(author,title,link,linkway,updatetime) values('$author',\"$title\",'$url',4,'$updatetime')ON DUPLICATE KEY UPDATE title=\"$title\",updatetime='$resultlast->updatetime'";
	echo $sql."</br>\n";
	$sqlresult=dh_mysql_query($sql);		
	$sql="update link set pageid = (select id from page where mediaid='$resultlast->mediaid') where link = '$url' and pageid is NULL" ;
	$sqlresult=dh_mysql_query($sql);
}

function update360linkpageid($resultlast,$author,$url,$title,$pageid)
{
	$updatetime=date("Y-m-d  H:i:s");
	$sql="insert into link(pageid,author,title,link,linkway,updatetime) values($pageid,'$author',\"$title\",'$url',4,'$updatetime')ON DUPLICATE KEY UPDATE title=\"$title\",updatetime='$resultlast->updatetime'";
	echo $sql."</br>\n";
	$sqlresult=dh_mysql_query($sql);		
}
?>  