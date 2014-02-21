<?php
require("../config.php");
require("../curl.php");
require("../common.php");
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
			echo "\n".$row['name'].'的全部文章:'.$row['rssurl']."</br>\n";
			$rssinfo->author=$row['name'];
			$authorid=$row['id'];
			$lastupdate=$row['updatetime'];			
			
			$buff = get_file_curl($row['rssurl']);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get rss file !</br>';	
				$sql="update author set failtimes=failtimes+1 where id = $authorid;";
				$result=dh_mysql_query($sql);
				continue;
			}
			readrssfile($buff,$rssinfo,$authorid,$lastupdate,$row['clinktype']);			
        }
	}
}

function readrssfile($buff,$rssinfo,$authorid,$lastupdate,$clinktype)
{
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	$buff =iconvbuff($buff);
	$buff = preg_replace('/encoding=".*?"/','encoding="UTF-8"',$buff);
	//echo $buff;
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
?>