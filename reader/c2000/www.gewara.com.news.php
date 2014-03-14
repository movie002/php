<?php
function readrssfile2008()
{
	$authorname='格瓦拉影讯';
	$authorurl='http://www.gewara.com';

	$url = array('http://www.gewara.com/news/cinema?pageNo=');
	$urlcat= array('电影资讯');
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
		$i=-1;
		while($change&&$i<3)
		{
			$i++;
			$trueurl = $eachurl.$i;
				
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
			//print_r($buff);
			preg_match_all('/<a href="([^>]+)" style="display:block; width:200px" title="([^>]+)" target="_blank" class="newImg center">(.*?)<\/a>/s',$buff,$match0);
			preg_match_all('/<p>([0-9]+)年([0-9]+)月([0-9]+)日<\/p>/s',$buff,$match1);			
			//print_r($match0);
			//print_r($match1);
			if(empty($match0[2]))
			{
				echo 'error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match1[1][$key2].'-'.$match1[2][$key2].'-'.$match1[3][$key2]));
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
				$rssinfo->cat = trim($urlcat[$key]);
				$rssinfo->link = $authorurl.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
	return;	
}
?>
