<?php
function www_mtime_com_trailer_php()
{
	$authorname='时光网短频';
	//$authorurl='http://www.mtime.com/review';
	print_r($authorname);

	$url = array('http://www.mtime.com/trailer/trailer/index',
				'http://www.mtime.com/trailer/behind_the_scene/index');
	$urlcat= array('预告片','花絮');	
	print_r($url);
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
				$trueurl = $eachurl.'.html';
			else
				$trueurl = $eachurl.'-'.$i.'.html';
			echo $trueurl."\n";
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;
			
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);				
			preg_match_all('/<a href="([^>]+)" target="\_blank">([^>]+) ([^>]+)<\/a><\/h3>/s',$buff,$match0);
			preg_match_all('/日期：([0-9]+)年([0-9]+)月([0-9]+)日<\/p>/s',$buff,$match1);
			//print_r($match0);
			//print_r($match1);
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[1] as $key1=>$div)			
			{
				$strpubdate=$match1[1][$key1].$match1[2][$key1].$match1[3][$key1];				
				$update = getrealtime($strpubdate);
				if($update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				if($newdate<$update)
					$newdate = $update;
				$cat =trim($urlcat[$key]);
				$link =trim($match0[1][$key1]);
				$title = "《".trim($match0[2][$key1])."》".$match0[3][$key1];	
				insertonlylink2($authorname,$title,$link,$cat,$update);
			}
		}
	}
}
?>