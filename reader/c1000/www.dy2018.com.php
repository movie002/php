<?php
function www_dy2018_com_php()
{
	$authorname='电影天堂';
	print_r($authorname);
	$authorurl='http://www.dy2018.com';

	$url = array('http://www.dy2018.com/html/gndy/dyzz/',
				'http://www.dy2018.com/html/tv/hytv/',
				'http://www.dy2018.com/html/tv/oumeitv/',
				'http://www.dy2018.com/html/tv/rihantv/',
				'http://www.dy2018.com/html/zongyi2013/',
				'http://www.dy2018.com/html/dongman/');
	$urlcat= array('最新电影',
					'华语电视剧',
					'欧美电视剧',
					'日韩电视剧',
					'综艺',
					'动漫');
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<4)
		{
			$i++;
			if($i==1)
				$trueurl = $eachurl.'index.html';
			else
				$trueurl = $eachurl.'index_'.$i.'.html';
			sleep(5);
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;	
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			
			preg_match_all('/<a href="([^>]+)" class="ulink" title="([^>]+)">(.*?)<\/a>/s',$buff,$match0);
			preg_match_all('/<font color="#8F8C89">日期：(.*?)点击：.*?<\/font>/s',$buff,$match1);			
			//print_r($match0);
			//print_r($match1);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =getrealtime($match1[1][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
