<?php
function readrssfile2006($author)
{
	if($author->rss!=2006)
		return;

	$url = array('http://news.mtime.com/movie/2/',
				'http://news.mtime.com/movie/1/',
				'http://news.mtime.com/movie/3/',
				'http://news.mtime.com/movie/4/',
				'http://news.mtime.com/tv/2/',
				'http://news.mtime.com/tv/1/',
				'http://news.mtime.com/tv/3/',
				'http://news.mtime.com/tv/4/');
	$urlcat= array('欧美电影','华语电影','日韩电影','其他电影','欧美电视','华语电视','日韩电视','其他电视');
		
	print_r($url);
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link2 where author='$author->name' and cat = '".$eachurlcat."'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
	
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<4)
		{
			$i++;
			if($i==1)
				$trueurl = $eachurl;
			else
				$trueurl = $eachurl.'index-'.$i.'.html';
			//echo $trueurl."\n";
			//continue;
			$buff = get_file_curl($trueurl);
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
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<li> <span>(.*?)月(.*?)日(.*?)<\/span><b>·<\/b><a href="(.*?)" target="_blank">(.*?)<\/a>(.*?)<\/li>/s',$buff,$match);
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'error no result!';
				return;
			}
			foreach ($match[2] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[1][$key2].'/'.$match[2][$key2].' '.$match[3][$key2]));
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
				$rssinfo->link =$author->rssurl.trim($match[4][$key2]);
				$rssinfo->title = $match[5][$key2];
				if(strstr($match[6][$key2],'图片新闻'))
					$rssinfo->title .= '[图片新闻]';
				if(strstr($match[6][$key2],'视频新闻'))
					$rssinfo->title .= '[视频新闻]';				
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}			
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>