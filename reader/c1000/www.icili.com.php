<?php
function www_icili_com_php()
{
	$authorname='爱磁力';
	print_r($authorname);
	
	$authorurl='http://www.icili.com/';

	$url = array('http://www.icili.com/emule/movie/',
				'http://www.icili.com/emule/series/',
				'http://www.icili.com/emule/cartoon/',
				'http://www.icili.com/emule/tv/');
	$urlcat= array('电影',
			'剧集',
			'动漫',
			'综艺');
			
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<3)
		{
			$i++;
			$trueurl = $eachurl.$i;
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;				

				echo "crawl ".$trueurl." </br>\n";
			print_r($buff);
			return;
			preg_match_all('/title="精华资源"><a href="(.*?)" title="(.*?)">(.*?)<\/a><\/h4>/s',$buff,$match0);		
			
			print_r($match0);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;			
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =$match0[3][$key2];
				//if($rssinfo->update<$updatetime[$key])
				//{
				//	///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
				//	echo "爬取到已经爬取文章，爬取继续! </br>\n";
				//	$change = false;	
				//	//break;
				//	continue;
				//}				
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
				//$rssinfo->cat = trim($urlcat[$key]).trim($match3[1][$key2]);
				$rssinfo->link = $authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime(true,$newdate,$authorname);
}
?>