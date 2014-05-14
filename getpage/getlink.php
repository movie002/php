<?php
//利用link修改page的状态
require("../config.php");
require("../common/compare.php");
require("../common/dbaction.php");
require("common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(7200); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getlink();
mysql_close($conn);

function getlink()
{
	$d=2;
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
	}
	$datebegin = getupdatebegin($d);	
	$sql="select * from onlylink where updatetime > '$datebegin' and fail < 3 and mtitle is not null";
//	$sql="select * from onlylink where mtitle is not null";

	if(isset($_REQUEST['pageid']))
	{
		$pageid = $_REQUEST['pageid'];
		$sql="select * from link where pageid=$pageid";	
	}
	if(isset($_REQUEST['link']))
	{
		$link = $_REQUEST['link'];
		$sql="select * from link where link='$link'";	
	}	
	
	echo $sql."</br>\n";
	$count=0;
	$results=dh_mysql_query($sql);
	while($row = mysql_fetch_array($results))
	{
		$count++;
		echo "\n".$count.": ";
		$maxrate=1;
		$pageid = getdbpageid($row['title'],$row['mtitle'],$row['moviecountry'],$row['movieyear'],$row['movietype'],$maxrate);
		if($pageid>=0)
		{
			//查找资源质量
			
			$sqlauthor="select * from author where name='".$row['author']."'";
			$resultsauthor=dh_mysql_query($sqlauthor);
			$rowauthor = mysql_fetch_array($resultsauthor);
			if(getlinkmeta($rowauthor,$linkway,$linktype,$linkquality,$linkdownway,$row['link'],$row['title'],$row['cat'])==-1)
				continue;	
			addorupdatelink($pageid,$row['author'],$row['title'],$row['link'],$row['cat'],$linkquality,$linkway,$linktype,$linkdownway,$row['updatetime']);
			$sqlupdate = "update page set updatetime = '".$row['updatetime']."' where id = '".$pageid."';";
			dh_mysql_query($sqlupdate);
			
			//删除onlylink中的部分
			$sql="delete from onlylink where link='".$row['link']."'";
			$sqlresult=dh_mysql_query($sql);
		}
	}
}
