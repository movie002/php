<?php
function readrssfile2004($author)
{
	if($author->rss!=2004)
		return;

	$url = array('http://ent.sina.com.cn/review/media/film/more.html',
				'http://ent.sina.com.cn/review/media/tv/more.html');
//	$url = array('http://ent.ifeng.com/movie/special/list_0/1.shtml');
				
	$urlcat= array('电影评论','电视评论','电影专题','电影评论','内地电视','港台电视','日韩电视','欧美电视','综艺节目');
	
	//$url = array('http://www.ed2000.com/FileList.asp?FileCategory=%E7%94%B5%E5%BD%B1');
	//$urlcat= array('电影');	
	print_r($url);
	
	//寻找各自的updatetime	
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link2 where author='$author->name' and cat = '".$eachurlcat."'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));	
	
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
			continue;
		}
		$buff = iconvbuff($buff);
		$rssinfo = new rssinfo();
		$rssinfo->author = $author->name;
		echo "crawl ".$eachurl." </br>\n";
		//print_r($buff);
		preg_match('/<tr><td colspan=2 class=f149>(.*?)<\/td><\/tr>/s',$buff,$match1);		
		preg_match_all('/<a href=(.*?) target=_blank>(.*?)<\/a><FONT SIZE=1 COLOR=#6666cc>\((.*?)月(.*?)日\)<\/font>/s',$match1[1],$match);
		//print_r($match);
		if(empty($match[1]))
		{				
			echo 'error no result!';
			return;
		}
		foreach ($match[1] as $key2=>$div)			
		{	
			$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[3][$key2].'/'.$match[4][$key2]));
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
			$rssinfo->link =trim($match[1][$key2]);
			$rssinfo->title = trim($match[2][$key2]);
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>