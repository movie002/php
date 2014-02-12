<?php 
/* 使用示例 */   
//header('Content-Type:text/html;charset= UTF-8'); 
//require("../../config.php");
//require("../../curl.php");
//require("../common.php");
//
//$douban_result = new MovieResult();
//$douban_result->title='致我们终将逝去的青春';
//get_m1905($douban_result);
//print_r($douban_result);

//处理电影名  
function get_m1905(&$resultlast)
{ 
	$name = rawurlencode($resultlast->title);   
	$buffer = get_file_curl('http://www.m1905.com/search/index-p-type-film-q-'.$name.'.html');
	//echo $buffer;
	if(false==$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
		return;
	}
	preg_match('/<h2 class="title\-mv".*?>.*?<a href="(.*?)" target="\_blank" title="(.*?)">.*?\((.*?)\).*?<\/a><\/h2>/s',$buffer,$match);
	preg_match('/http:\/\/www\.m1905\.com\/mdb\/film\/(.*?)\//s',$match[1],$match1);
	//print_r($match1);
	$m1905id=$match1[1];
	preg_match('/<2>(.*?)<\/2>/s',$resultlast->ids,$match);
	if(empty($match[1]))
	{
		$resultlast->ids .= '<2>'.$m1905id.'</2>';	
	}		
	get_m1905_detail($m1905id,$resultlast);
	return true;
}

function get_m1905_detail($m1905id,&$resultlast) 
{
	$buffer= get_file_curl('http://www.m1905.com/mdb/film/'.$m1905id.'/');
	//echo $buffer;
	
	preg_match('/<span class="pr05 fb"><a href=".*?" class="tabmenu">预告片<\/a><a href="(.*?)" class="pl05 f12 laBlueS_f fn">\((.*?)\)<\/a><\/span>/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$resultlast->m1905trail = '《'.$resultlast->title.'》m1905 预告 '.'('.$match[2].')';
		$resultlast->m1905trailurl = $match[1];
	}	
	preg_match('/<span class="pr05 fb"><a href=".*?" class="tabmenu">视频<\/a><a href="(.*?)" class="pl05 f12 laBlueS_f fn">\((.*?)\)<\/a><\/span>/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$resultlast->m1905sp =  '《'.$resultlast->title.'》m1905 新闻视频'.'('.$match[2].')';
		$resultlast->m1905spurl = $match[1];
	}
	//评分
	preg_match('/<span class="score">(.*?)<\/span><span class="nop">\(<q id="rating_num">(.*?)<\/q>人评分\)<\/span>/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$newr2='<r2>'.$match[1].'('.$match[1].')</r2>';	
		preg_match('/<r2>(.*?)<\/r2>/s',$resultlast->ids,$match3);
		if(empty($match3[1]))
		{
			$resultlast->ids .=$newr2;
		}
		else
		{
			$resultlast->ids = preg_replace('/<r2>(.*?)<\/r2>/s',$newr2,$resultlast->ids);
		}
	}
}
?>  