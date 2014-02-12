<?php
function readrssfile1006($author)
{
	if($author->rss!=1006)
		return;

	$url = array('http://www.y80s.com/guoyu/',
				'http://www.y80s.com/waiyu/',
				'http://www.y80s.com/tvs/',
				'http://www.y80s.com/dm/');
	$urlcat= array('国语电影','外语电影','电视剧','动漫');
	print_r($url);
	
	//寻找各自的updatetime	
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$author->name' and cat = '".$eachurlcat."'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
	
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	foreach ($url as $key=>$eachurl)
	{
		$buff = get_file_curl($eachurl);
		//如果失败，就使用就标记失败次数
		if(!$buff)
		{
			echo 'fail to get file '.$author->rssurl."!</br>\n";	
			$sql="update author set failtimes=failtimes+1 where id = $author->id;";
			$result=dh_mysql_query($sql);
			$i++;
			continue;
		}
		$buff = iconvbuff($buff);
		$rssinfo = new rssinfo();
		$rssinfo->author = $author->name;
		echo "crawl ".$eachurl." </br>\n";
		//print_r($buff);	
		preg_match('/<ul class="me1 clearfix">(.*?)<\/ul>/s',$buff,$match);
		
		preg_match_all('/<li> <a href="(.*?)" target="_blank" title="(.*?)">.*?<img id=".*?" _src="(.*?)".*?<\/li>/s',$match[1],$match1);		
		//print_r($match1);
		//return;
		if(empty($match1[1]))
		{
			echo 'error no result!';
			return;
		}
		$eachupdate=date("Y-m-d H:i:s");
		foreach ($match1[1] as $key2=>$div)			
		{	
			//提取日期
			//echo $match1[3][$key2];
			preg_match('/\/(20[0-9]{6})_/',$match1[3][$key2],$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match2[1]));
				$eachupdate = $rssinfo->update;
			}
			else
			{
				$rssinfo->update=$eachupdate;
			}
			if($rssinfo->update > date("Y-m-d H:i:s",strtotime("+3 days")))
			{
				//将年份减1
				$rssinfo->update = date("Y-m-d H:i:s",strtotime('-1 year',strtotime($rssinfo->update)));
			}				
			
			if($rssinfo->update<$updatetime[$key])
			{
				echo "爬取到已经爬取文章，爬取结束! </br>\n";
				$change = false;	
				break;
				//continue;
			}
			if($newdate<$rssinfo->update)
				$newdate = $rssinfo->update;
			$rssinfo->cat =trim($urlcat[$key]);
			$rssinfo->link =$author->rssurl.trim($match1[1][$key2]);
			preg_match('/\[(.*?)\]/',$match1[2][$key2],$match2);
			//if(!empty($match2[1]))
			//{
			//	$rssinfo->title = $match2[1];
			//}
			//else
			//{
			//	$rssinfo->title = $match1[2][$key2];
			//}
			$rssinfo->title = $match1[2][$key2];
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>