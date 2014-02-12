<?php
function readrssfile1011($author)
{
	if($author->rss!=1011)
		return;

	$url = array('http://henbt.com/');
	$urlcat= array('henBT');
	print_r($url);
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$author->name' and cat like '%".$eachurlcat."%'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
	
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=1;
		while($change&&$i<4)
		{
			$trueurl = $eachurl.$i.'.html';
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get file '.$author->rssurl."!</br>\n";	
				$sql="update author set failtimes=failtimes+1 where id = $author->id;";
				$result=dh_mysql_query($sql);
				$i++;
				continue;
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $author->name;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<tr class="[^"]+">[\s]+<td nowrap="nowrap">([^"]+)<\/td>[\s]+<td><a href="[^"]+"><font color=[^\s]+><b>([^\s]+)<\/b><\/font><\/a><\/td>[\s]+<td style="text-align:left;">[\s]+<a href="(.*?)" target="_blank">(.*?)<\/a>.*?<\/td>.*?<td>(.*?)<\/td>.*?<td><a href=".*?" class=".*?">(.*?)<\/a><\/td>.*?<\/tr>/s',$buff,$match);
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'error no result!';
				return;
			}
			foreach ($match[2] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[1][$key2]));
				if($rssinfo->update > date("Y-m-d H:i:s",strtotime("+3 days")))
				{
					//将年份减1
					$rssinfo->update = date("Y-m-d H:i:s",strtotime('-1 year',strtotime($rssinfo->update)));
				}			
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
				$rssinfo->cat =trim($urlcat[$key]).','.trim($match[2][$key2]);
				$rssinfo->link =$author->rssurl.trim($match[3][$key2]);
				$rssinfo->title = trim($match[4][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}			
			$i++;
		}
	}
	setupdatetime(true,$newdate,$author->id);
	return;	
}
?>
