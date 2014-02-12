<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../curl.php");
//require("../../common.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//$douban_result = new MovieResult();
//$douban_result->title='圣灵破碎者';
//#$douban_result->aka='名侦探柯南/铁甲奇侠/Iron Man';
//$douban_result->type=4;
//$douban_result->updatetime='2000-05-05 00:00:00';
//get_vbaidu($douban_result);
//print_r($douban_result);
//mysql_close($conn);

//处理电影名  
function get_vbaidu(&$resultlast,$pageid=-1)
{ 
	echo " \n begin to get from vbaidu:\n";	
	//$name = rawurlencode($resultlast->title);
	$name = rawurlencode(iconvbuffgbk($resultlast->title));
	
	$buffer = get_file_curl('http://v.baidu.com/v?word='.$name);

	if(false==$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
		return;
	}
	//判断类型和名字
	$buffer = iconvbuff($buffer);
	//echo $buffer;
	
//	preg_match('/<div class="special-area-cont">(.*?)<\/div>[\s]+<div class="normalarea">/s',$buffer,$match0);	
//	print_r($match0);
//	if(empty($match0[1]))
//		return;
	
	preg_match_all('/<div class="info-sec">(.*?)<\/div>[\s]+<\/div>[\s]+<\/div>[\s]+<\/div>/s',$buffer,$match1);	
	//print_r($match1);
	if(empty($match1[1]))
		return;	
		
	foreach($match1[1] as $key=>$each)
	{	
		preg_match('/<a href="(.*?)" target="\_blank" .*? title="([^>]+)">[\s]+<span>(.*?)<\/span>[\s]+<\/a>/s',$each,$match2);
		//print_r($match2);
		if(empty($match2[0]))
			continue;
		preg_match('/(.*?)\?.*?/s',$match2[1],$matchurl);
		//print_r($matchurl);
		if(empty($matchurl))
			$url=$match2[1];
		else
			$url=$matchurl[1];
					
		//判断类型
		$thistype=0;
		if(strstr($url,"http://v.baidu.com/movie/"))
			$thistype=1;
		if(strstr($url,"http://v.baidu.com/tv/"))
			$thistype=2;
		if(strstr($url,"http://v.baidu.com/show/"))
			$thistype=3;
		if(strstr($url,"http://v.baidu.com/comic/"))
			$thistype=4;
		if($resultlast->type!=0 && $thistype!=$resultlast->type)
			continue;
			
		$title=trim($match2[2]);
		//比较名字是否一致
		if($title!=$resultlast->title)
		{
			$akas =explode('/',$resultlast->aka);
			//print_r($akas);			
			if (array_search($title,$akas)==false)
				continue;
		}
		
		$titleaka = trim($match2[3]);
		$titleaka = preg_replace('/<font.*?>(.*?)<\/font>/s','${1}',$titleaka);
		$titleaka = preg_replace('/[\s]+\&nbsp;/s',' ',$titleaka);
		//print_r($titleaka);
		
		$title = $titleaka;
		
		//如果是电影，找出是否能播放
		if($thistype==1)
		{
			preg_match('/d\-url="([^>]+)"/s',$each,$match4);
			//print_r($match4);
			if(empty($match4[0]))
				continue;
			else
			{
				preg_match('/<span style="color:#999;">(.*?)<\/span>/s',$each,$match3);
				//print_r($match3);
				if(!empty($match3[0]))
				{
					$title .=str_replace('&nbsp;',"",$match3[1]); 
				}
				preg_match('/(.*?)\?.*?/s',$match4[1],$matchurl1);
				//print_r($matchurl);
				if(empty($matchurl1))
					$url1=$match4[1];
				else
					$url1=$matchurl1[1];				
				
				preg_match('/http:\/\/(.*?)\//s',$url1,$match5);
				$thisauthor=$match5[1];
				update360link($resultlast,$thisauthor,$url1,$title,3,$pageid);
			}			
		}
		//如果是其他，找出播放的集数		
		else
		{
			preg_match('/<span class="update\-info">(.*?)<\/span>/s',$each,$match3);
			//print_r($match3);
			if(!empty($match3[0]))
			{
				$title .='('.preg_replace('/<b.*?>(.*?)<\/b>/s','${1}',$match3[1]).')'; 
			}
		}

		update360link($resultlast,'百度视频',$url,$title,3,$pageid);
		echo $url."--> v2345 -->".$title."\n";
	}
}
?>  