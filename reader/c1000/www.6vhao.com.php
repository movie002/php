<?php
function www_6vhao_com_php()
{
	$authorname='6V电影';
	print_r($authorname);
	
	$authorurl='http://www.6vhao.com/';

	$url = array('http://www.6vhao.com/dy/','http://www.6vhao.com/gq/','http://www.6vhao.com/mj/','http://www.6vhao.com/rj/','http://www.6vhao.com/dlz/');
	$urlcat= array('电影','高清电影','欧美电视剧','日韩电视剧','国产电视剧');
			
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<1)
		{
			sleep(1);
			$i++;
			$trueurl = $eachurl.'index.html';
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			
			preg_match_all('/<li><span>([0-9-]+)<\/span><a href="(.*?)" target="\_blank">(.*?)<\/a><\/li>/s',$buff,$match0);				
			//print_r($match0);
			
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;			
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update = getrealtime($match0[1][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					///由于置顶和推荐导致时间小的放在前面，这里固定爬取2页,保证爬到
					echo "爬取到已经爬取文章，爬取继续! </br>\n";
					$change = false;	
					//break;
					continue;
				}				
				
				$rssinfo->cat = $urlcat[$key];
				$rssinfo->link =trim($match0[2][$key2]);
				$title=str_replace('<font color=\'#FF0000\'>','',$match0[3][$key2]);
				$title=str_replace('</font>','',$title);
				$rssinfo->title = trim($title);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>