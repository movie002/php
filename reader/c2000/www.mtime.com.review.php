<?php
function www_mtime_com_review_php()
{
	$authorname='时光网评论';
	$authorurl='http://www.mtime.com/review';
	print_r($authorname);

	$url = array('http://www.mtime.com/review/column/',
				'http://www.mtime.com/review/mediareview/');
	$urlcat= array('影评人专栏','媒体评论');	
	print_r($url);
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link2 where author='$authorname' and cat like '%".$eachurlcat."%'";
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
			if($i==1)
				$trueurl = $eachurl;
			else
				$trueurl = $eachurl.'index-'.$i.'.html';
			//echo $trueurl."\n";
			//continue;
			$buff = get_file_curl($trueurl);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				sleep(5);
				$buff = get_file_curl($trueurl);
				if(!$buff)
				{
					echo 'fail to get file '.$trueurl."!</br>\n";	
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
			if($key==0)
				preg_match_all('/<h2 class="px14"><a href="(.*?)" title=".*?" target="_blank">(.*?)<\/a><\/h2>		<div class="fix">	<span class="fr">(.*?)<\/span>	<span class="c_666">评<\/span><a href=".*?" title="(.*?)" target="_blank">.*?<\/a>.*?<\/div>/s',$buff,$match);
			else
				preg_match_all('/<strong class="px14"><a href="(.*?)" title=".*?" target="_blank">(.*?)<\/a><\/strong>	<p class="c_666 fix mt6">	<span class="fl"><a href=".*?" title=".*?" method=".*?" userid=".*?" target="_blank">(.*?)<\/a><em class="mr3 ml6">评<\/em><a href=".*?" title="(.*?)" target="_blank">.*?<\/a><\/span>			<span class="fr">(.*?)<\/span>/s',$buff,$match);	
			//print_r($match);
			if(empty($match[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[2] as $key2=>$div)			
			{
				if($key==0)
				{
					$strpubdate=$match[3][$key2];					
				}
				else
				{
					$strpubdate=$match[5][$key2];
				}	
				$strpubdate=str_replace('：',':',$strpubdate);				
				$rssinfo->update = getrealtime($strpubdate);			
			
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
				if($key==0)
					$rssinfo->title = $rssinfo->cat.'评《'.$match[4][$key2].'》:'.$match[2][$key2];
				else
					$rssinfo->title = $match[3][$key2].'评《'.$match[4][$key2].'》:'.$match[2][$key2];		
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
	setupdatetime2(true,$newdate,$authorname);
}
?>