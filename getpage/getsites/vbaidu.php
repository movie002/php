<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/dbaction.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//get_vbaidu('超级笑星','名侦探柯南/铁甲奇侠/Iron Man',3,'2000-05-05 00:00:00',4);
//mysql_close($conn);

//处理电影名  
function get_vbaidu($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from vbaidu:\n";	
	//$name = rawurlencode($title);
	//$name = rawurlencode(iconvbuffgbk($title));
	$url='http://v.baidu.com/#word='.$title;
	echo $url."\n";
	$buffer = get_file_curl($url);

	if(false==$buffer)
	{
		echo $title."搜索失败 </br>\n";
		return;
	}
	//判断类型和名字
	$buffer = iconvbuff($buffer);
	echo $buffer;

	preg_match_all('/<a href="([^>\"]+)" class="le\-btn le\-btn\-green" data\-logger="ctype=detail"><span class="btn-icon">立即播放<\/span><\/a>/s',$buffer,$match0);	
	//print_r($match0);
	if(empty($match0[1]))
		return;
	return;
	
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
		if($type!=0 && $thistype!=$type)
			continue;
			
		$title=trim($match2[2]);
		//比较名字是否一致
		if($title!=$title)
		{
			$akas =explode('/',$aka);
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

		addorupdatelink($pageid,'百度视频',$title,$url,'',4,7,7,0,0,$updatetime,1);
	}
}
?>  