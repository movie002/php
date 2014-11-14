<?php
function ent_sina_com_cn_review_php()
{
	$authorname='新浪评论';
	$authorurl='http://ent.sina.com.cn/review/';
	print_r($authorname);

	$url = array('http://ent.sina.com.cn/review/media/film/more.html',
				'http://ent.sina.com.cn/review/media/tv/more.html');
				
	$urlcat= array('电影评论','电视评论');	
	print_r($url);
	
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
		$trueurl=$eachurl;
		$buff = geturl($trueurl,$authorname);
		//如果失败，就使用就标记失败次数
		if(!$buff)
			continue;	
		$rssinfo = new rssinfo();
		$rssinfo->author = $authorname;
		echo "crawl ".$eachurl." </br>\n";
		//print_r($buff);
		preg_match('/<tr><td colspan=2 class=f149>(.*?)<\/td><\/tr>/s',$buff,$match1);		
		preg_match_all('/<a href=(.*?) target=_blank>(.*?)<\/a><FONT SIZE=1 COLOR=#6666cc>\((.*?)月(.*?)日\)<\/font>/s',$match1[1],$match);
		//print_r($match);
		if(empty($match[1]))
		{				
			echo 'preg buff error no result!';
			continue;
		}
		foreach ($match[1] as $key2=>$div)			
		{	
			$rssinfo->update =getrealtime($match[3][$key2].'/'.$match[4][$key2]);
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
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
}
?>