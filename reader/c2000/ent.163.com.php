<?php
function ent_163_com_php()
{
	$authorname='网易娱乐';
	$authorurl='http://ent.163.com';
	print_r($authorname);

	$url = array('http://ent.163.com/special/00031HA4/ch_news',
				'http://ent.163.com/special/00031HA4/eu_news',
				'http://ent.163.com/special/00031HA4/jp_news',
				'http://ent.163.com/special/00031HA4/moviecomments',
				'http://ent.163.com/special/00031HA4/dycy_news',
				'http://ent.163.com/special/00034IG6/tvbaogao',
				'http://ent.163.com/special/00034J9Q/tvdjch',
				'http://ent.163.com/special/000346H5/neidiju',
				'http://ent.163.com/special/000346H5/gangtaiju',
				'http://ent.163.com/special/000346H5/oumeiju',
				'http://ent.163.com/special/00032VQS/zongyijiemu');				
	$urlcat= array('华语电影',
					'海外电影',
					'电影专题',
					'电影评论',
					'电影活动',
					'电视剧观看报告',
					'电视剧独家策划',
					'华语电视',
					'港台电视',
					'欧美日韩电视',
					'综艺节目');
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
		$i=0;
		while($change&&$i<4)
		{
			$i++;
			if($i==1)
				$trueurl = $eachurl.'.html';
			else
				$trueurl = $eachurl.'_0'.$i.'.html';
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

			preg_match_all('/<h3 class="bigsize "><a href="(.*?)">(.*?)<\/a><\/h3>/s',$buff,$match);
			preg_match_all('/<p class="sourceDate"><span>.*?<\/span>(.*?)<\/p>/s',$buff,$match0);
			//print_r($match);
			//print_r($match0);
			
			if(empty($match[1]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[1] as $key2=>$div)			
			{	
				$rssinfo->update = getrealtime($match0[1][$key2]);
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