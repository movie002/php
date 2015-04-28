<?php
//	$authorname='网易娱乐';
//	$authorurl='http://ent.163.com';//如果页面的链接中有http信息，需要为空
//	$url = array('http://ent.163.com/special/00031HA4/ch_news',
//				'http://ent.163.com/special/00031HA4/eu_news')/
//	$urlcat= array('华语电影', '海外电影');


function c_common_read($authorname,$authorurl,$url,$urlcat,$preg1,$preg2,$eachurlp,$icount=3)
{
	print_r($authorname);
	print_r($authorurl);
	print_r($url);
	print_r($urlcat);
	//寻找各自的updatetime	
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<$icount)
		{
			sleep(2);
			$i++;
            $trueurl = str_replace('%eachurl%',$eachurl,$eachurlp);
            $trueurl = str_replace('%i%',$i,$trueurl);
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				break;

			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			preg_match_all($preg1,$buff,$match1);
			preg_match_all($preg2,$buff,$match2);
			//print_r($match1);
			//print_r($match2);
			if(empty($match1[2]))
			{			
				echo 'preg buff error no result!';
				continue;
			}	
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;	
			foreach ($match1[1] as $key2=>$div)			
			{
				$rssinfo->update = getrealtime($match2[1][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match1[1][$key2]);
				$rssinfo->title = trim($match1[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
