<?php
function www_mtime_com_peoplereview_php()
{
	$authorname='时光网特约评论';
	$authorurl='http://www.mtime.com/review/column/';
	print_r($authorname);

	$url = array('http://www.mtime.com/review/column/');
	$urlcat= array('影评人专栏');	
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
			preg_match_all('/<h2 class="px14"><a href="(.*?)" title=".*?" target="_blank">(.*?)<\/a><\/h2>		<div class="fix">	<span class="fr">(.*?)<\/span>	<span class="c_666">评<\/span><a href=".*?" title="(.*?)" target="_blank">.*?<\/a>.*?<\/div>/s',$buff,$match);
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[2] as $key2=>$div)			
			{
				if($key==0)
				{
					$strpubdate=$match[3][$key2];					
				}
				else
				{
					$strpubdate=$match[5][$key2];
				}	
				$strpubdate=str_replace('：',':',$strpubdate);				
				$rssinfo->update = getrealtime($strpubdate);			
			
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match[1][$key2]);
				$rssinfo->title = $rssinfo->cat.'评《'.$match[4][$key2].'》:'.$match[2][$key2];
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
