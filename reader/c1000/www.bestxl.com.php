<?php
function readrssfile1002()
{
	$authorname='老调网';
	$authorurl='http://www.bestxl.com/';

	$url = array('http://www.bestxl.com/');
	$urlcat= array('bestxl');
	print_r($url);
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$authorname' and cat like '%".$eachurlcat."%'";
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
			$i++;
			$trueurl = $eachurl.'index.php?page='.$i;
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get file '.$trueurl."!</br>\n";	
				$sql="update author set failtimes=failtimes+1 where author='$authorname';";
				$result=dh_mysql_query($sql);
				continue;
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			print_r($buff);	
			return;
			preg_match_all('/<tr class="[^"]+">[\s]+<td nowrap="nowrap">([^"]+)<\/td>[\s]+<td><a href="[^"]+"><font color=[^\s]+><b>([^\s]+)<\/b><\/font><\/a><\/td>[\s]+<td style="text-align:left;">[\s]+<a href="(.*?)" target="_blank">(.*?)<\/a>.*?<\/td>.*?<td>(.*?)<\/td>.*?<td><a href=".*?" class=".*?">(.*?)<\/a><\/td>.*?<\/tr>/s',$buff,$match);
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'error no result!';
				continue;
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
		}
	}
	setupdatetime2(true,$newdate,$authorname);
	return;	
}
?>
