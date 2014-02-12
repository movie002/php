<?php
function readrssfile1007($author)
{
	if($author->rss!=1007)
		return;

	$url = array('http://www.yyets.com/resourcelist');
	print_r($url);
	
	//寻找各自的updatetime	
	$updatetime = array();	
	
	$sql="select max(updatetime) from link where author='$author->name'";
	$sqlresult=dh_mysql_query($sql);
	$row = mysql_fetch_array($sqlresult);
	array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	
	print_r($updatetime);
	
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=1;
		while($change&&$i<7)
		{
			$trueurl = $eachurl.'?page='.$i;
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
			
			preg_match_all('/<dt>.*?<a target="_blank" href="(.*?)">【(.*?)】<strong>(.*?)<\/strong><\/a>(.*?)<\/dt>.*?<dd class="list_a">.*?【更新】<\/font>(.*?)<\/span><\/dd>/s',$buff,$match);			
			//print_r($match);

			if(empty($match[1]))
			{
				echo 'error no result!';
				return;
			}
			
			foreach ($match[1] as $key2=>$div)			
			{	
				preg_match('/^(.*?)\|/',$match[5][$key2],$match1);
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match1[1]));
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
						
				list($time,$time2,$cat) = split('[|]',$match[5][$key2]);
					
				$rssinfo->cat = trim($match[2][$key2]).','.trim($cat);
				$rssinfo->link =trim($match[1][$key2]);
				$rssinfo->title = trim($match[3][$key2]).' '.trim($match[4][$key2]);
				
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