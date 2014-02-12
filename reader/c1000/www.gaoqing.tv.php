<?php
function readrssfile1003($author)
{
	if($author->rss!=1003)
		return;

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
	preg_match_all("/<div id=\"scroll_list\"><div style=\"float:left;\"><li>\[<a href=.*?>(.*?)<\/a>\]<a href=(.*?) target=_blank title=(.*?)>.*?. (.*?)<\/a><\/li><\/div><div style=\"float:right;line-height:22px;padding-right:3px;\">(.*?)<\/div><\/div>/i",$buff,$match);
	//print_r($match);
	//continue;
	if(empty($match[1]))
	{
		echo 'error no result!';
		return;
	}
	foreach ($match[1] as $key=>$div)
	{
		//echo $li;
		preg_match_all('/([0-9]{1,2}).([0-9]{1,2})/',$match[5][$key],$match1);		
		$rssinfo->update =date("Y-m-d H:i:s",strtotime(date('Y').'-'.$match1[1][0].'-'.$match1[2][0]));
		if($rssinfo->update > date("Y-m-d H:i:s",strtotime("+3 days")))
		{
			//将年份减1
			$rssinfo->update = date("Y-m-d H:i:s",strtotime('-1 year',strtotime($rssinfo->update)));
		}
		if($rssinfo->update<$author->updatetime)
		{
			echo "爬取到已经爬取文章，爬取结束! </br>\n";
			break;
			//continue;
		}
		$change = true;
		if($newdate<$rssinfo->update)
			$newdate = $rssinfo->update;

		$rssinfo->cat =trim($match[1][$key]);
		$rssinfo->link =$author->rssurl.trim($match[2][$key]);
		$title = preg_replace('/\[.*?\]/','',$match[3][$key]);
		$title = preg_replace('/韩影\:|韩剧\:/','',$title);
		$title = preg_replace('/^&nbsp;/','',$title);
		$title = preg_replace('/&nbsp.*?$/','',$title);
		$rssinfo->title ='['.$rssinfo->cat.']'.trim($title).' ('.$match[4][$key].')';
		//print_r($rssinfo);
		insertonlylink($rssinfo);
	}
	setupdatetime($change,$newdate,$author->id);
	return;
}
?>