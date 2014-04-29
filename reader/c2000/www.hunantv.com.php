<?php
function www_hunantv_com_php()
{
	$authorname='金鹰网';
	$authorurl='http://www.hunantv.com';
	print_r($authorname);

	$url = array('http://www.hunantv.com/movie/news/',
				'http://www.hunantv.com/opinion/movie/',
				'http://www.hunantv.com/tv/news/',
				'http://www.hunantv.com/opinion/tv/',
				'http://www.hunantv.com/show/news/',
				'http://www.hunantv.com/opinion/show/');
	$urlcat= array('电影资讯','电影评论','电视资讯','电视评论','综艺资讯','综艺评论');
	print_r($url);
	
	//寻找各自的updatetime	
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
		while($change&&$i<3)
		{
			$i++;
			if($i===1)
				$trueurl = $eachurl;
			else
				$trueurl = $eachurl.'list_'.$i.'.html';
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				sleep(5);
				$buff = get_file_curl($trueurl);
				if(!$buff)
				{
					echo 'error: fail to get file '.$trueurl."!</br>\n";	
					$sql="update author set failtimes=failtimes+1 where name='$authorname';";
					$result=dh_mysql_query($sql);
					continue;
				}
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<li><span class="title"><a href="(.*?)" target="_blank">(.*?)<\/a><\/span><span class="time">(.*?)<\/span><\/li>/s',$buff,$match);
			//print_r($match);
			if(empty($match[1]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[1] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[3][$key2]));
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
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =trim($match[1][$key2]);
				$rssinfo->title = trim($match[2][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
}
?>