<?php
function readrssfile1009()
{
	$authorname='BT天堂';
	$authorurl='http://www.bttiantang.com';

	$url = array('http://www.bttiantang.com/?PageNo=');
	$urlcat= array('电影');
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
		$i=0;
		while($change&&$i<4)
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
			//return;
			preg_match_all('/<p class="tt cl"><span>([^>]+)<\/span><a href="([^>]+)" target="\_blank"><b>([^>]+)<i>\/(.*?)<\/i>(.*?)<\/b><\/a><\/p>/s',$buff,$match0);		
			//print_r($match0);
			if(empty($match0[2]))
			{
				echo 'error no result!';
				return;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match0[1][$key2]));
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match0[2][$key2]);
				$rssinfo->title = '《'.trim($match0[3][$key2]).'》'.trim($match0[4][$key2]).trim($match0[5][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
	return;	
}
?>
