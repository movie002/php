<?php
function www_51oscar_com_php()
{
	$authorname='大众影评网';

	print_r($authorname);
	$authorurl='http://www.51oscar.com';
	$url = array('http://news.51oscar.com/',
				 'http://review.51oscar.com/');				
	$urlcat= array('资讯','影评');
		
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
			$trueurl = $eachurl.'?&p='.$i;
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				break;

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			if($key==1)
				preg_match_all('/<a href="\/detail\/([^>]+)"[^>]+title="([^>]+)">([^>]+)<\/a>/s',$buff,$match);
			else
				preg_match_all('/<a href="\/detail\/([^>]+) class="romance_title" +title="([^>]+)">([^>]+)<\/a>/s',$buff,$match);
			preg_match_all('/<em style="margin-left:20px;">(.*?)<\/em><\/p>/s',$buff,$match1);
			if(empty($match1[1]))
			{
				preg_match_all('/发表于：(.*?)<\/dd>/s',$buff,$match1);
			}
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
					//break;
					continue;
				}
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.'/detail/'.trim($match[1][$key2]);
				$rssinfo->title = trim($match[3][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>