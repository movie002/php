<?php
function news_mtime_com_php()
{
	$authorname='时光网新闻';
	$authorurl='http://news.mtime.com/';
	print_r($authorname);
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
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<4)
		{
			sleep(2);
			$i++;
			if($i==1)
				$trueurl = $eachurl;
			else
				$trueurl = $eachurl.'index-'.$i.'.html';
			//echo $trueurl."\n";
			//continue;
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<h3>[\s]+<a href="http:\/\/news\.mtime\.com\/([0-9|\/]+)\.html" target="\_blank">([^>]+)<\/a><\/h3>/s',$buff,$match);
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[2] as $key2=>$div)			
			{	
				preg_match('/([0-9]+\/[0-9]+\/[0-9]+)\/[0-9]+/s',$match[1][$key2],$matchdate);
				//print_r($matchdate);
				$rssinfo->update = getrealtime($matchdate[1]);			
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match[1][$key2]).".html";
				$rssinfo->title = $match[2][$key2];				
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>