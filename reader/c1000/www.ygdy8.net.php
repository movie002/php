<?php
function www_ygdy8_net_php()
{
	$authorname='阳光电影';
	print_r($authorname);
	$authorurl='http://www.ygdy8.com/';
	$urlcat= array('最新电影下载','迅雷电影资源','华语剧集专区','日韩剧集专区','欧美剧集专区','迅雷综艺节目','迅雷动漫资源');
	
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);	
		
	$buff = geturl($authorurl,$authorname);
	//如果失败，就使用就标记失败次数
	if(!$buff)
		continue;	
				
	$rssinfo = new rssinfo();
	$rssinfo->author = $authorname;
	
	
	$change = false;	
	preg_match_all("/<table[^>]*+>([^<]*+(?:(?!<\/?+table)<[^<]*+)*+)<\/table>/i",$buff,$match);
	//print_r($match);	
	foreach ($match[1] as $key=>$table)
	{
		echo $key."\n";
		if($key>=8||$key==0)
		{
			continue;
		}
		preg_match_all("/<tr[^>]*+>([^<]*+(?:(?!<\/?+tr)<[^<]*+)*+)<\/tr>/i",$table,$match1);
		//print_r($match1);
		foreach ($match1[1] as $tr)
		{
			preg_match_all("/<td[^>]*+>([^<]*+(?:(?!<\/?+td)<[^<]*+)*+)<\/td>/i",$tr,$match2);
			//if(!empty($match1[1]))
			//print_r($match2);
			if(!empty($match2[1]))
			{
				preg_match("/<font color=#FF0000>(.*?)<\/font>/i",$match2[1][1],$match3);	
				//print_r($match3);
				$rssinfo->update = getrealtime($match3[1]);
				if($rssinfo->update<$updatetime[$key-1])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					break;
					//continue;
				}
				$change = true;
				
					
				preg_match("/\[<a href=\".*?\">(.*?)<\/a>\]/i",$match2[1][0],$match3);
				//print_r($match3);
				$rssinfo->cat =trim($match3[1]);
				
				preg_match("/<a href='(.*?)'>(.*?)<\/a>/i",$match2[1][0],$match3);
				//print_r($match3);
				$rssinfo->title =trim($match3[2]);
				$rssinfo->link = substr($authorurl,0,strlen($authorurl)-1).trim($match3[1]);
				insertonlylink($rssinfo);
			}			
		}
	}
}
?>