<?php
function www_hd180_com_php()
{
	$authorname='HD180';
	print_r($authorname);
	
	$authorurl='http://www.hd180.com/';

	$url = array('http://www.hd180.com');
	$urlcat= array('电影');
			
	print_r($url);
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<1)
		{
			$i++;
			$trueurl = $eachurl;
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;				

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
		
			preg_match_all('/<td width=470px><a href="(.*?)" title="(.*?)" target=\_blank>(.*?)<\/a><\/td>/s',$buff,$match0);
			preg_match_all('/<td width=237px class="newlineborder" align=center>(.*?)<\/td>/s',$buff,$match1);
			
			//print_r($match0);
			//print_r($match1);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;			
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update = date("Y-m-d H:i:s",strtotime($match1[1][$key2]));
				if($rssinfo->update<$updatetime[$key])
				{
					///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
					echo "爬取到已经爬取文章，爬取继续! </br>\n";
					$change = false;	
					//break;
					continue;
				}
				$rssinfo->cat = $urlcat[$key];
				$rssinfo->link =$authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>