<?php
function www_bttiantang_com_php()
{
	$authorname='BT天堂';
	print_r($authorname);
	$authorurl='http://www.bttiantang.com';

	$url = array('http://www.bttiantang.com/?PageNo=');
	$urlcat= array('电影');
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<4)
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
			preg_match_all('/<p class="tt cl"><span>([^>]+)<\/span><a href="([^>]+)" target="\_blank"><b>([^>]+)<i>\/(.*?)<\/i>(.*?)<\/b><\/a><\/p>/s',$buff,$match0);		
			//print_r($match0);
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =getrealtime($match0[1][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}				
				
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match0[2][$key2]);
				$rssinfo->title = '《'.trim($match0[3][$key2]).'》'.trim($match0[4][$key2]).trim($match0[5][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
