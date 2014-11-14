<?php
function www_icili_com_php()
{
	$authorname='爱磁力';
	print_r($authorname);
	
	$authorurl='http://www.icili.com';

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
			//print_r($buff);
			preg_match_all('/title="精华资源"><a href="(.*?)" title="(.*?)">(.*?)<\/a><\/h4>/s',$buff,$match0);				
			preg_match_all('/<p>发布时间：(.*?) 更新时间：(.*?)<\/p>/s',$buff,$match1);	
			//print_r($match0);
			//print_r($match1);

			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;			
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =$match1[1][$key2];
				if($rssinfo->update<$updatetime[$key])
				{
					///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
					echo "爬取到已经爬取文章，爬取继续! </br>\n";
					$change = false;	
					//break;
					continue;
				}				
				
				$rssinfo->cat = $urlcat[$key];
				$rssinfo->link = $authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>