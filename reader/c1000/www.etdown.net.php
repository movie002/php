<?php
function www_etdown_net_php()
{
	$authorname='光影资源联盟';
	print_r($authorname);
	$authorurl='http://www.etdown.net/';

	$url = array('http://www.etdown.net/index-');
	$urlcat= array('');
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<2)
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
			preg_match_all('/<a href=\'\.\/k\-([0-9]+)\' target=\'_blank\'>([^>]+)<\/a>/s',$buff,$match0);
			preg_match_all('/<td align="left"  nowrap="" class="list2">(.*?)<\/td>/s',$buff,$match1);
			preg_match_all('/<td nowrap="" class="list1" abbr=\'[0-9]+\'>([0-9]+)\.(.*?)<\/td>/s',$buff,$match2);
			preg_match_all('/<td nowrap="" class="list2"><a href="\.\/c\-[0-9]+">(.*?)<\/a><\/td>/s',$buff,$match3);			
			
			//print_r($match0);
			//print_r($match1);
			//print_r($match2);
			//print_r($match3);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =getrealtime(date("Y").'-'.$match2[1][$key2].'-'.$match2[2][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
					echo "爬取到已经爬取文章，爬取继续! </br>\n";
					$change = false;	
					//break;
					continue;
				}				
				
				$rssinfo->cat = trim($urlcat[$key]).trim($match3[1][$key2]);
				$rssinfo->link = $authorurl.'k-'.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]).trim($match2[1][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>