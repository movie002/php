<?php
function readrssfile1005($author)
{
	if($author->rss!=1005)
		return;

	$url = array('http://www.piaohua.com/html/dianying.html','http://www.piaohua.com/html/daylianxuju.html');
	$urlcat= array('电影','电视剧');
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
		preg_match_all('/<li><a href="(.*?)"class="img"><img src=".*?"alt="(.*?)"width="120"height="150"border="0"\/><\/a><a href=".*?"><strong>.*?<\/strong><\/a><span>(.*?)<\/span><\/li>/',$buff,$match);
		//print_r($match);
		if(empty($match[1]))
		{
			echo 'error no result!';
			continue;
		}
		foreach ($match[1] as $key2=>$div)			
		{	
			//有就去除 <font color=red>2013-05-26</font>，没有就取值
			$pubdate = str_replace('<font color=red>','',$match[3][$key2]);
			$pubdate = str_replace('</font>','',$pubdate);
			$rssinfo->update =date("Y-m-d H:i:s",strtotime($pubdate));
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
			$rssinfo->link =$author->rssurl.trim($match[1][$key2]);
			$title = str_replace("<font color='#FF0000'>",'',$match[2][$key2]);
			$title = str_replace('</font>','',$title);			
			$rssinfo->title =$title;
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>