<?php
function www_dy558_com_php()
{
	$authorname='电影下载';
	print_r($authorname);
	
	$authorurl='http://www.dy558.com';

	$timenow = time();
	$timebegin = $timenow - 24*3600;
	$timeend = $timenow + 24*3600;
	$datetoday = date("Y-m-d",$timeend);
	$datebegin = date("Y-m-d",$timebegin);
	
	$urlx = 'http://www.dy558.com/down/search.php?fromdate='.$datebegin.'&todate='.$datetoday.'&ordertype=1&search=1&page=';
	$url = array($urlx);
	$urlcat= array('');
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
				
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			preg_match_all('/<td><h2><a href="(.*?)" target="\_blank" class="searchtitle">([^>]+)<\/a><\/h2><span class="datetime float\_right"> \[([0-9\-]+)\]<\/span><\/td>/s',$buff,$match0);		
			
			//print_r($match0);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
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
				
				//$rssinfo->cat = trim($urlcat[$key]).trim($match3[1][$key2]);
				$rssinfo->link = $authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>