<?php
require("../config.php");
require("../common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(9999); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");
$siteurl='http://www.mtime.com/trailer/trailer/index.html';
reader();
//readerdirect();
mysql_close($conn);


function reader()
{
	global $conn,$siteurl;
	$sql="select * from author where rssurl='$siteurl'";
	echo $sql;
	$results=mysql_query($sql,$conn);	
	if($results)
	{	
		$rssinfo = new rssinfo(); 	
		while($row = mysql_fetch_array($results))
		{	 
			//打开rss地址，并读取，读取失败则中止
			echo $row['name']."的全部文章:</br>\n";
			$rssinfo->author=$row['name'];
			$rssinfo->quality=$row['quality'];
			$rssinfo->way=$row['way'];
			$rssinfo->type=$row['type'];
			$rssinfo->contain=$row['contain'];
			$authorid=$row['id'];
			$lastupdate=$row['updatetime'];		
			
			$buff = get_file_curl($row['rssurl']);
			//如果失败，就使用就标记失败次数
			if(!$buff)
			{
				echo 'fail to get file !</br>';	
				$sql="update author set failtimes=failtimes+1 where id = $authorid;";
				$result=mysql_query($sql,$conn);
				echo 'update fail info:'.mysql_error().'</br>';	
				continue;
			}
			mb_detect_order('UTF-8,gbk,gb2312,cp950');
			$encoding = mb_detect_encoding($buff);
			$buff = iconv($encoding,"UTF-8//IGNORE", $buff);				
			readrssfile($buff,$rssinfo,$authorid,$lastupdate);			
        }
	}
}

function readerdirect()
{
	global $siteurl;
	//打开rss地址，并读取，读取失败则中止
	echo $siteurl."的全部文章:\n";
	$buff = get_file_curl($siteurl);
	//如果失败，就使用就标记失败次数
	if(!$buff)
	{
		echo 'fail to get file !</br>';	
		return;
	}
	//$buff = mb_convert_encoding($buff, "UTF-8", "GBK");
	//print_r($buff);
	//readrssfile($buff,$siteurl,'3',1,'20130111',1,1);		
}

function readrssfile($buff,$rssinfo,$authorid,$lastupdate)
{
	global $conn,$siteurl;;
	$newdate = date("Y-m-d H:i:s",strtotime('0000-00-00 00:00:00'));
	
	$change = false;
	preg_match_all("/<ul class=\"clearfix\"[^>]*+>([^<]*+(?:(?!<\/?+ul)<[^<]*+)*+)<\/ul>/i",$buff,$match);
	//print_r($match);
	foreach ($match[1] as $key=>$div)
	{
//		echo $key."\n";
//		if($key>=3)
//		{
//			break;
//		}
		preg_match_all("/<li[^>]*+>([^<]*+(?:(?!<\/?+li)<[^<]*+)*+)<\/li>/i",$div,$match1);
		//print_r($match1);
		foreach ($match1[1] as $li)
		{
			preg_match("/<h3 class=\"normal mt6\"> <a href=\"(.*?)\" target=\"_blank\">(.*?)<\/a><\/h3>/i",$li,$match2);
			//print_r($match2);
			$title = trim($match2[2]);		
			preg_match("/([^ ]+)/u",$title,$title1);
			//print_r($title1);
			$rssinfo->title =trim($title1[0]);
			$rssinfo->link = $match2[1];		
			preg_match("/<p class=\"mt6\"> 日期：(.*?)<\/p>/i",$li,$match3);
			//print_r($match3);
			delete_word( $match3[1],'年'); 
			delete_word( $match3[1],'月'); 
			delete_word( $match3[1],'日'); 
			$rssinfo->update = date("Y-m-d H:i:s",strtotime($match3[1]));
			//print_r($result);
			if($rssinfo->update<$lastupdate)
			{
				break;
				//continue;
			}			
			$change = true;			
			if($rssinfo->update > $newdate)
				$newdate = $rssinfo->update;
			updatelink($rssinfo);
		}
	}
	setupdatetime($change,$newdate,$authorid);
	return;
}
?>