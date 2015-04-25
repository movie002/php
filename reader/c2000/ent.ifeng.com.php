<?php
function ent_ifeng_com_php()
{
	$authorname='凤凰网娱乐';

	print_r($authorname);
	$authorurl='http://ent.ifeng.com';
	$url = array('http://ent.ifeng.com/listpage/44169/',
				 'http://ent.ifeng.com/listpage/44168/');				
	$urlcat= array('电影资讯','电视资讯');
		
	print_r($url);
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<3)
		{
			sleep(2);
			$i++;
			$trueurl = $eachurl.$i.'/list.shtml';
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				break;

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			preg_match_all('/<h2><a href="([^"]+)" target="_blank" title="([^"]+)">[^>]+<\/a><\/h2>/s',$buff,$match);
			preg_match_all('/<span>([0-9\-\s\:]+)<\/span>/s',$buff,$match1);
			//print_r($match);
			//print_r($match1);
			if(empty($match[2]))
			{			
				echo 'preg buff error no result!';
				continue;
			}	
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;	
			foreach ($match[1] as $key2=>$div)			
			{
				$rssinfo->update = getrealtime($match1[1][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =trim($match[1][$key2]);
				$rssinfo->title = trim($match[2][$key2]);
				print_r($rssinfo);
				//insertonlylink($rssinfo);
			}
		}
	}
}
?>
