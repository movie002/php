<?php
function www_sfscn_com_php()
{
	$authorname='海上电影';
	$authorurl='http://www.sfs-cn.com/';
	print_r($authorname);

	$url = array('http://www.sfs-cn.com/node3/node8319/index.html');
				
	$urlcat= array('电影资讯');	
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
		preg_match_all('/<a class="p12" href=(.*?) target=\_blank><span style="color:#000">(.*?)<\/span><\/a>/s',$buff,$match0);
		preg_match_all('/<td width="15%"><span style="color:#00009F">(.*?)<\/span><\/td>/s',$buff,$match1);
		
		//print_r($match0);
		//print_r($match1);
		if(empty($match0[1]))
		{				
			echo 'preg buff error no result!';
			continue;
		}
		foreach ($match0[1] as $key2=>$div)			
		{		
			$rssinfo->update = $match1[1][$key2];	
			if($rssinfo->update<$updatetime[$key])
			{
				echo "爬取到已经爬取文章，爬取结束! </br>\n";	
				break;
				//continue;
			}
			$rssinfo->cat =trim($urlcat[$key]);
			$rssinfo->link = $authorurl.trim($match0[1][$key2]);
			$rssinfo->title = trim($match0[2][$key2]);
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
}
?>