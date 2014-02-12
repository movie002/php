<?php
function readrssfile1009($author)
{
	if($author->rss!=1009)
		return;

	$url = array('http://www.7060.com/new/movie.html',
				'http://www.7060.com/new/tv.html',
				'http://www.7060.com/new/dm.html',
				'http://www.7060.com/new/zy.html');
	$urlcat= array('电影','电视剧','动漫','综艺');
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
		preg_match_all('/<span class="name"><a href="(.*?)">(.*?)<\/a>.*?<span class="time">(.*?)<\/span>/s',$buff,$match);	
		//print_r($match);
		if(empty($match[1]))
		{
			echo 'error no result!';
			return;
		}
		foreach ($match[1] as $key2=>$div)			
		{	
			//提取日期
			$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[3][$key2]));
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
			if($newdate<$rssinfo->update)
				$newdate = $rssinfo->update;
			$rssinfo->cat =trim($urlcat[$key]);
			$rssinfo->link =$author->rssurl.trim($match[1][$key2]);
			$rssinfo->title ='《'.trim($match[2][$key2]).'》手机电影在线下载 ';
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>