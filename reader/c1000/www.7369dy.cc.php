<?php
function readrssfile1002($author)
{
	if($author->rss!=1002)
		return;
	$urlcat= array('电影','电视剧','动漫%\' or cat like \'%综艺');
	//寻找各自的updatetime	
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$author->name' and (cat like '%".$eachurlcat."%')";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
		
	$buff = get_file_curl($author->rssurl);
	//如果失败，就使用就标记失败次数
	if(!$buff)
	{
		echo 'fail to get file '.$author->rssurl."!</br>\n";	
		$sql="update author set failtimes=failtimes+1 where id = $author->id;";
		$result=dh_mysql_query($sql);
		return;
	}		
	$buff = iconvbuff($buff);
	$rssinfo = new rssinfo();
	$rssinfo->author = $author->name;
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	
	$change = false;	
	preg_match_all("/<div class=\"content\"[^>]*+>([^<]*+(?:(?!<\/?+div)<[^<]*+)*+)<\/div>/i",$buff,$match);
	//print_r($match);
	if(empty($match[1]))
	{
		echo 'error no result!';
		return;
	}
	foreach ($match[1] as $key=>$div)
	{
		echo $key."\n";
		if($key>=3)
		{
			break;
		}
		//echo $div;
		preg_match_all("/<li[^>]*+>([^<]*+(?:(?!<\/?+li)<[^<]*+)*+)<\/li>/i",$div,$match1);
		//print_r($match1);
		//最新电影资源
		foreach ($match1[1] as $li)
		{
			//echo $li;
			preg_match("/<font color=red>(.*?)<\/font>/i",$li,$match2);
			//print_r($match2);
			$year = date("Y");
			$localpubdate =  $year.'-'.$match2[1];
			$rssinfo->update = date("Y-m-d H:i:s",strtotime($localpubdate));						
			if($rssinfo->update > date("Y-m-d H:i:s",strtotime("+3 days")))
			{
				//将年份减1
				$rssinfo->update = date("Y-m-d H:i:s",strtotime('-1 year',strtotime($rssinfo->update)));
			}	
			if($rssinfo->update<$updatetime[$key])
			{
				echo "爬取到已经爬取文章，爬取结束! </br>\n";
				break;
				//continue;
			}			
			$change = true;
			if($rssinfo->update > $newdate)
				$newdate = $rssinfo->update;
				
			preg_match("/<span class=\"leixing\"><a href=.*?target=\"_blank\">(.*?)<\/a><\/span>/i",$li,$match3);
			//print_r($match3);
			$rssinfo->cat =trim($match3[1]);
			preg_match("/span><a href=\"(.*?)\"[^>]*+>([^<]*+(?:(?!<\/?+a)<[^<]*+)*+)<\/a>/i",$li,$match3);
			//print_r($match3);
			$rssinfo->title =trim($match3[2]);
			//echo $result->title . "</br>\n";			
			$rssinfo->link = substr($author->rssurl,0,strlen($author->rssurl)-1).trim($match3[1]);
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	setupdatetime($change,$newdate,$author->id);
	return;
}
?>