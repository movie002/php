<?php
function www_piaohua_com_php()
{
	$authorname='飘花资源网';
	print_r($authorname);
	$authorurl='http://www.piaohua.com/';

	$url = array('http://www.piaohua.com/html/dianying.html',
		'http://www.piaohua.com/html/daylianxuju.html');
	$urlcat= array('电影','电视剧');
	print_r($url);
	
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
        $trueurl = $eachurl;
		$buff = geturl($trueurl,$authorname);
		//如果失败，就使用就标记失败次数
		if(!$buff)
			continue;	

		$rssinfo = new rssinfo();
		$rssinfo->author = $authorname;
		echo "crawl ".$eachurl." </br>\n";
		//print_r($buff);	
		preg_match_all('/<li><a href="(.*?)"class="img"><img src=".*?"alt="(.*?)"width="120"height="150"border="0"\/><\/a><a href=".*?"><strong>.*?<\/strong><\/a><span>(.*?)<\/span><\/li>/',$buff,$match);
		//print_r($match);
		if(empty($match[1]))
		{
			echo 'preg buff error no result!';
			continue;
		}
		foreach ($match[1] as $key2=>$div)			
		{	
			//有就去除 <font color=red>2013-05-26</font>，没有就取值
			$pubdate = str_replace('<font color=red>','',$match[3][$key2]);
			$pubdate = str_replace('</font>','',$pubdate);
			$rssinfo->update =getrealtime($pubdate);			
			if($rssinfo->update<$updatetime[$key])
			{
				echo "爬取到已经爬取文章，爬取结束! </br>\n";
				$change = false;	
				break;
				//continue;
			}
			$rssinfo->cat =trim($urlcat[$key]);
			$rssinfo->link =$authorurl.trim($match[1][$key2]);
			$title = str_replace("<font color='#FF0000'>",'',$match[2][$key2]);
			$title = str_replace('</font>','',$title);			
			$rssinfo->title =$title;
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
}
?>
