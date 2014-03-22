<?php
function www_somag_net_php()
{
	$authorname='搜磁力链接';
	print_r($authorname);
	$authorurl='http://www.somag.net';
	
	$url = array('http://www.somag.net/category/%E7%94%B5%E5%BD%B1/',
				 'http://www.somag.net/category/%E7%BE%8E%E5%89%A7/',
				 'http://www.somag.net/category/%E6%97%A5%E5%89%A7/',
				 'http://www.somag.net/category/%E9%9F%A9%E5%89%A7/',
				 'http://www.somag.net/category/%E5%8A%A8%E6%BC%AB/',
				 'http://www.somag.net/category/3D/');
	$urlcat= array('电影',
			'美剧',
			'日剧',
			'韩剧',
			'动漫',
			'3D');	
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
		while($change&&$i<3)
		{
			$i++;
			$trueurl = $eachurl.$i.'/';
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get file '.$trueurl."!</br>\n";	
				$sql="update author set failtimes=failtimes+1 where name='$authorname';";
				$result=dh_mysql_query($sql);
				continue;
			}
			$buff = iconvbuff($buff);
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<h2><a href="(.*?)" target="\_blank">(.*?)<\/a><\/h2>/s',$buff,$match);
			preg_match_all('/发布于 (.*?)\-/s',$buff,$match2);
			preg_match_all('/大小(.*?)<\/div>/s',$buff,$match3);
			//print_r($match);
			//print_r($match2);
			//print_r($match3);
			if(empty($match[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[2] as $key2=>$div)			
			{	
				$rssinfo->update =getrealtime($match2[1][$key2]);			
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
				$rssinfo->link =$authorurl.trim($match[1][$key2]);
				//为了减轻后续的压力，这里根据此网站的特点对标题进行处理
				//取出最有可能是标题的地方
				preg_match('/(.*?)(1080|720|3D|20|18|19|\[|US|\(|S0[1-9]E[0-9]{2}|E[0-9]{2})/s',$match[2][$key2],$matchtitle);
				//print_r($matchtitle);
				if(empty($matchtitle)||empty($matchtitle[1]))
					continue;
				//echo $match[2][$key2];
				preg_match_all('/1080p|720p|S0[1-9]E[0-9]{2}|E[0-9]{2}|BluRay|HD/s',$match[2][$key2],$matchq);
				$titlemata='';
				if(!empty($matchq[0]))
				foreach($matchq[0] as $key3=> $eachmatchq)
				{
					$titlemata.='.'.$eachmatchq;
				}
				//print_r($matchq);
				$lasttitle = str_replace('.',' ',$matchtitle[1]);
				//$rssinfo->title = $match[2][$key2].'('.trim($match3[1][$key2]).')';
				$rssinfo->title = "《".trim($lasttitle)."》".trim($titlemata).'('.trim($match3[1][$key2]).')';
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
}
?>