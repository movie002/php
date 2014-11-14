<?php
function www_vvtor_com_php()
{
	$authorname='思享';
	print_r($authorname);
	
	$authorurl='http://www.vvtor.com';

	$url = array('http://www.vvtor.com/daily');
	$urlcat= array('电影');
			
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<1)
		{
			$i++;
			$trueurl = $eachurl;
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;				

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			
			preg_match_all('/<h2 class="entry\-title"><a href="(.*?)" title="(.*?)" rel="bookmark" target="\_blank">(.*?)<\/a><\/h2>/s',$buff,$match0);				
			//print_r($match0);

			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;			
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update = date("Y-m-d H:i:s");
				if($rssinfo->update<$updatetime[$key])
				{
					///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
					echo "爬取到已经爬取文章，爬取继续! </br>\n";
					$change = false;	
					//break;
					continue;
				}				
				
				$rssinfo->cat = $urlcat[$key];
				$rssinfo->link =trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>