<?php
function www_gewara_com_activity_php()
{
	$authorname='格瓦拉活动';
	$authorurl='http://www.gewara.com';
	print_r($authorname);
	
	$url = array('http://www.gewara.com/activity/activityList.xhtml?pageNo=');
	$urlcat= array('电影活动');
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
		while($change&&$i<2)
		{
			$i++;
			$trueurl = $eachurl.$i;
				
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'error: fail to get file '.$trueurl."!</br>\n";	
				$sql="update author set failtimes=failtimes+1 where author='$authorname';";
				$result=dh_mysql_query($sql);
				continue;
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			preg_match_all('/<a href="\/activity\/([0-9]+)" title=">([^>]+)" target="_blank">[^>]+<\/a>/s',$buff,$match0);
			preg_match_all('/([0-9]+)月([0-9]+)日 &nbsp;\-\-&nbsp;([0-9]+)月([0-9]+)日/s',$buff,$match1);			
			//print_r($match0);
			//print_r($match1);
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =getrealtime(date("Y").'-'.$match1[3][$key2].'-'.$match1[4][$key2]);
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}					
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
				$rssinfo->cat = trim($urlcat[$key]);
				$rssinfo->link = $authorurl.'/activity/'.trim($match0[1][$key2]);
				$rssinfo->title = trim($match0[2][$key2]).'('.$match1[1][$key2].'月'.$match1[2][$key2].'日-'.$match1[3][$key2].'月'.$match1[3][$key2].'日)';
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
}
?>
