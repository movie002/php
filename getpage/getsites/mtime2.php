<?php 
/* 使用示例 */   
header('Content-Type:text/html;charset= UTF-8'); 
require("../../config.php");
require("../../curl.php");
require("../common.php");

$douban_input = new SearchInput();
$douban_input->title='致我们终将逝去的青春';
$douban_input->country='1';
$douban_input->year='2012';
$douban_input->type='1';
$douban_result = new MovieResult();
get_mtime($douban_input,$douban_result);
get_mtime_detail(&$douban_result);
print_r($douban_result);

//处理电影名  
function get_mtime($input,&$resultlast)  
{ 
	//设置默认值
	echo "title country type year </br>\n";
	echo $input->title.' '.$input->country.' '.$input->type.' '.$input->year."</br>\n";
	$titles=processtitle($input->title);
	print_r($titles);
	$titlesearch = array();
	array_push($titlesearch,$titles[0]);
	if(count($titles)>1)
	{
		foreach($titles as $eachtitle)
			array_push($titlesearch,$eachtitle);
		$titlesearch[0]=implode('/',$titles);			
	}
	
	//print_r($titlesearch);
	$doubanresults=array();
	$maxrate=0;	
	foreach($titlesearch as $eachtitlesearch)
	{
		echo "</br>\n -----search-->".$eachtitlesearch .":</br>\n";
		$name = rawurlencode($eachtitlesearch);
//http://service.channel.mtime.com/service/search.mcs?Ajax_CallBack=true&Ajax_CallBackType=Mtime.Channel.Pages.SearchService&Ajax_CallBackMethod=SearchMovieByCategory&Ajax_RequestUrl=http%3A%2F%2Fmovie.mtime.com%2Fmovie%2Fsearch%2Fsection%2F%3Fyear%3D2000&Ajax_CallBackArgument0=&Ajax_CallBackArgument1=0&Ajax_CallBackArgument2=0&Ajax_CallBackArgument3=0&Ajax_CallBackArgument4=0&Ajax_CallBackArgument5=0&Ajax_CallBackArgument6=0&Ajax_CallBackArgument7=0&Ajax_CallBackArgument8=&Ajax_CallBackArgument9=2000&Ajax_CallBackArgument10=2000&Ajax_CallBackArgument11=0&Ajax_CallBackArgument12=0&Ajax_CallBackArgument13=0&Ajax_CallBackArgument14=1&Ajax_CallBackArgument15=0&Ajax_CallBackArgument16=1&Ajax_CallBackArgument17=4&Ajax_CallBackArgument18=1&Ajax_CallBackArgument19=0
		//$buffer = get_file_curl('http://service.channel.mtime.com/service/search.mcs?Ajax_CallBack=true&Ajax_CallBackType=Mtime.Community.Controls.CommunityPages.SearchMovie&Ajax_CallBackMethod=GetSearchResult&Ajax_RequestUrl=http%3A%2F%2search.mtime.com%2search%2movie?type=movie&=%e8%87%b4%e9%9d%92%e6%98%a5');
		$buffer = get_file_curl('http://search.mtime.com/search/theater?%E8%87%B4%E9%9D%92%E6%98%A5');
		echo $buffer;
		if(false==$buffer)
		{
			echo $eachtitlesearch."搜索失败 </br>\n";
			continue;
			//http://api.mtime.com/callbackServices.m?Ajax_CallBack=true&type=movie&=%e8%87%b4%e9%9d%92%e6%98%a5&Ajax_CallBackType=Mtime.Community.Controls.CommunityPages.SearchMovie&Ajax_CallBackMethod=GetSearchResult&Ajax_RequestUrl=http%3A%2F%2search.mtime.com%2search%2movie?type=movie&=%e8%87%b4%e9%9d%92%e6%98%a5
		}
		echo $buffer;
		return;
		preg_match('/<h2 class="title\-mv">.*?<a href="(.*?)" target="\_blank" title=".*?">(.*?)<\/a><\/h2>/s',$buffer,$match);
		//print_r($match);
		preg_match('/\((.*?)\)/s',$match[2],$match1);
		//print_r($match1);
		$year=$match1[1];	
		$title =trim(preg_replace('/<b>.*?<\/b>/s','',$match[2]));
		preg_match('/http:\/\/www\.m1905\.com\/mdb\/film\/(.*?)\//s',$match[1],$match1);
		//print_r($match1);
		$m1905id=$match1[1];
		if(!c_movieyear($input->year,$year,2,$title))
			continue;			
		$rate = c_title($input->title,$title);
			echo ' rate: '.$rate;
		if($rate>=3)
		{
			$resultlast->m1905id = $m1905id;
			return true;
		}						
		if($rate>$maxrate)
		{
			$maxrate = $rate;
			$resultlast->m1905id = $m1905id;
		}
	}
	if($maxrate<=0)
		return false;
	else
		return true;
}

function get_mtime_detail(&$resultlast) 
{
	$buffer= get_file_curl('http://www.m1905.com/mdb/film/'.$resultlast->m1905id.'/');
	echo $buffer;
	
	preg_match('/<span class="pr05 fb"><a href=".*?" class="tabmenu">预告片<\/a><a href="(.*?)" class="pl05 f12 laBlueS_f fn">\((.*?)\)<\/a><\/span>/s',$buffer,$match);
	print_r($match);
	$resultlast->m1905trail = 'm1905 预告 '.'('.$match[2].')';
	$resultlast->m1905trailurl = $match[1];
	
	preg_match('/<span class="pr05 fb"><a href=".*?" class="tabmenu">视频<\/a><a href="(.*?)" class="pl05 f12 laBlueS_f fn">\((.*?)\)<\/a><\/span>/s',$buffer,$match);
	print_r($match);
	$resultlast->m1905sp = 'm1905 视频 '.'('.$match[2].')';
	$resultlast->m1905spurl = $match[1];
	
	//评分
	preg_match("/var totalvote = '(.*?)';.*?var score = '(.*?)';/s",$buffer,$match);
	print_r($match);
	if(!empty($match[1]))
	{
		$resultlast->meta .='<r1>'.$match[2].'('.$match[1].')</r1>';
	}
}
?>  