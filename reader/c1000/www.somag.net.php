<?php
function readrssfile1008()
{
	$authorname='搜磁力链接';
	
	$url = array('http://www.somag.net/category/%E7%94%B5%E5%BD%B1/',
				 'http://www.somag.net/category/%E7%BE%8E%E5%89%A7/',
				 'http://www.somag.net/category/%E6%97%A5%E5%89%A7/',
				 'http://www.somag.net/category/%E9%9F%A9%E5%89%A7/',
				 'http://www.somag.net/category/%E5%8A%A8%E6%BC%AB/',
				 'http://www.somag.net/category/3D/');
	$urlcat= array('电影','美剧','日剧','韩剧','动漫','3D');
	
	//$url = array('http://www.ed2000.com/FileList.asp?FileCategory=%E7%94%B5%E5%BD%B1');
	//$urlcat= array('电影');	
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
		while($change&&$i<6)
		{
			$i++;
			$trueurl = $eachurl.$i.'/';
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get file '.$trueurl."!</br>\n";	
				$sql="update author set failtimes=failtimes+1 where author='$authorname';";
				$result=dh_mysql_query($sql);
				$i++;
				continue;
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<h2><a href="(.*?)" target="\_blank">(.*?)<\/a><\/h2>/s',$buff,$match);
			print_r($match);
			continue;
			if(empty($match[2]))
			{
				echo 'error no result!';
				return;
			}
			foreach ($match[2] as $key2=>$div)			
			{	
				$rssinfo->update =date("Y-m-d H:i:s",strtotime($match[4][$key2]));
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
				$cat = trim($match[1][$key2]);
				if($urlcat[$key]==='动漫')
					if($cat!='电视动画'&&$cat!='剧场动画'&&$cat!='OVA'&&$cat!='新番连载')
						continue;
				$rssinfo->cat =trim($urlcat[$key]).','.trim($match[1][$key2]);
				//$rssinfo->link =$author->rssurl.trim($match[2][$key2]);
				$title = $match[3][$key2];
				if($match[5][$key2]=='<img src="Images/magnet-icon-14w-14h.gif">')
				{
					$rssinfo->cat .= ',磁力链接';
					$rssinfo->title =$title;
				}
				else
				{
					$rssinfo->cat .= ',电驴链接';
					$rssinfo->title =$title.' ('.$match[5][$key2].')';
				}
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
	return;	
}
?>