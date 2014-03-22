<?php
require("../config.php");
require("../common/curl.php");
require("../common/common.php");
header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");
all();
mysql_close($conn);

function all()
{
	$sql="select * from author where rss = 1";
	
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$sql="select * from author where rss = 1 and id=$id";	
	}		
//	if(isset($_GET['id']))
//	{
//		$id = $_GET['id'];
//		$sql="select * from author where rss = 1 and id=$id";	
//	}
	echo $sql."</br>\n";
	$results=dh_mysql_query($sql);
	if($results)
	{
		$rssinfo = new rssinfo();
		while($row = mysql_fetch_array($results))
		{
			//打开rss地址，并读取，读取失败则中止
			echo "\n".$row['id'].' -- '.$row['name'].'的全部文章:'.$row['rssurl']."</br>\n";
			$rssinfo->author=$row['name'];
			$authorid=$row['id'];
			$lastupdate=$row['updatetime'];			
			
			$buff = get_file_curl($row['rssurl']);
			//如果失败，就使用就标记失败次数
			if($buff == false)
			{
				sleep(5);
				$buff = get_file_curl($row['rssurl']);
				if(false==$buff)
				{
					echo 'fail to get rss file !</br>';	
					$sql="update author set failtimes=failtimes+1 where id = $authorid;";
					$result=dh_mysql_query($sql);
					continue;
				}
			}
			//readrssfile($buff,$rssinfo,$authorid,$lastupdate);
			pregrssfile($buff,$rssinfo,$authorid,$lastupdate);
        }
	}
}

function pregrssfile($buff,$rssinfo,$authorid,$lastupdate)
{
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	$buff =iconvbuff($buff);
	$buff = preg_replace('/encoding=".*?"/','encoding="UTF-8"',$buff);
	//echo $buff;
	//查找所有的item
	preg_match_all('/<item>([\s\S]*?)<\/item>/i',$buff,$match);
	//print_r($match);
	if(!empty($match[1]))
	{	
		$change = false;
		foreach ($match[1] as $matcheach)
		{
			preg_match('/<pubDate>(.*?)<\/pubDate>/i',$matcheach,$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				$rssinfo->update = getrealtime($match2[1]);
				if($rssinfo->update<=$lastupdate)
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					break;
					//continue;
				}
				$change = true;
				if($newdate<$rssinfo->update)
					$newdate = $rssinfo->update;
			}
			$rssinfo->title='';
			$rssinfo->link='';
			$rssinfo->cat='';
			
			preg_match('/<title>(.*?)<\/title>/is',$matcheach,$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				$rssinfo->title = getrealname($match2[1]);
			}
			preg_match('/<link>(.*?)<\/link>/is',$matcheach,$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				$rssinfo->link = getrealname($match2[1]);
			}
			
			preg_match_all('/<category>(.*?)<\/category>/is',$matcheach,$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				foreach($match2[1] as $key=> $eachcat)
					$rssinfo->cat .=' '.getrealname($eachcat);
			}			
			preg_match_all('/<categoryname>(.*?)<\/categoryname>/is',$matcheach,$match2);
			//print_r($match2);
			if(!empty($match2[1]))
			{
				foreach($match2[1] as $key=> $eachcat)
					$rssinfo->cat .=' '.getrealname($eachcat);
			}			
			//开始写入数据库信息
			//print_r($rssinfo);
			insertonlylink($rssinfo);
		}
	}
	//setupdatetime($change,$newdate,$authorid);
}

function readrssfile($buff,$rssinfo,$authorid,$lastupdate)
{
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	$buff =iconvbuff($buff);
	$buff = preg_replace('/encoding=".*?"/','encoding="UTF-8"',$buff);
	//echo $buff;
	//$buff = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $buff);
	$buff = simplexml_load_string($buff,null,LIBXML_NOCDATA);
	//print_r($buff);
	$change = false;
	echo "开始爬取".$rssinfo->author." ";
	//首先取得rss的更新时间
	if(!empty($buff->channel->lastBuildDate))
	{
		$newdate = getrealtime(date("Y-m-d H:i:s",strtotime($buff->channel->lastBuildDate)));
		echo $newdate." vs ".$lastupdate."</br> \n";
		if($newdate<$lastupdate)
		{
			echo "无最新内容，不爬取! </br> \n";
			return;
		}	
	}
	
	foreach ($buff->channel->item as $matcheach)
	{	
		$matcheach = (array)$matcheach;
		//print_r($matcheach);
		if(!empty($matcheach['pubDate']))
		{
			$update=trim((string)$matcheach['pubDate']);
			$rssinfo->update = getrealtime(date("Y-m-d H:i:s",strtotime($update)));
			if($rssinfo->update<$lastupdate)
			{
				echo "爬取到已经爬取文章，爬取结束! </br>\n";
				break;
				//continue;
			}
			$change = true;
			if($newdate<$rssinfo->update)
				$newdate = $rssinfo->update;
		}		
		if(!empty($matcheach['title']))
		{
			$rssinfo->title =trim($matcheach['title']);
		}
		else
			$rssinfo->title='';
		
		if(!empty($matcheach['link']))
		{
			$rssinfo->link = trim((string)$matcheach['link']);
		}
		else
			$rssinfo->link ='';
			
		if(!empty($matcheach['category']))
		{
			if(is_array($matcheach['category']))
				$rssinfo->cat = implode(',',$matcheach['category']);
			else
				$rssinfo->cat = trim($matcheach['category']);
		}
		else if(!empty($matcheach['categoryname']))
		{
			if(is_array($matcheach['categoryname']))
				$rssinfo->cat = implode(',',$matcheach['categoryname']);
			else
				$rssinfo->cat = trim($matcheach['categoryname']);			
		}
		else
			$rssinfo->cat='';
		//开始写入数据库信息
		//print_r($rssinfo);
		insertonlylink($rssinfo);
	}
	setupdatetime($change,$newdate,$authorid);
}

function getrealname($name)
{
	$name = preg_replace('/\<\!\[CDATA\[(.*?)\]\]\>/s','$1',trim($name));
	return trim($name);
}
?>