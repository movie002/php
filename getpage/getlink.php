<?php
// 将onlylink中找出pageid之后，插入link表
//	从数据库查找pageid
//	匹配系数为1，开始的匹配系数较高，防止匹配出错
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
	$sql="select * from onlylink where mtitle is not null and fail < 3";
	if( isset($_REQUEST['d']))
	{
		$d=2;
		$d = $_REQUEST['d'];
		$datebegin = getupdatebegin($d,'onlylink');
		$sql .= " and updatetime > '$datebegin'";
	}
	if(isset($_REQUEST['a']))
	{
		$a = $_REQUEST['a'];
		$sql .= " and author = '$a'";
	}
	
	echo $sql."</br>\n";
	$count=0;
	$results=dh_mysql_query($sql);
	while($row = mysql_fetch_array($results))
	{
		$count++;
		echo "\n".$count.": ".$row['title'].': '.$row['link'].': '.$row['cat'].' --> '.$row['movietype'].'/'.$row['moviecountry'].'/'.$row['movieyear'];
		$maxrate=1;
		$pageid = getdbpageid($row['title'],$row['mtitle'],$row['moviecountry'],$row['movieyear'],$row['movietype'],$maxrate);
		if($pageid>=0)
		{
			//查找资源质量			
			$sqlauthor="select * from author where name='".$row['author']."'";
			$resultsauthor=dh_mysql_query($sqlauthor);
			$rowauthor = mysql_fetch_array($resultsauthor);
			if(getlinkmeta($rowauthor,$linkway,$linktype,$linkquality,$linkdownway,$linkvalue,$row['link'],$row['title'],$row['cat'])==-1)
				continue;	
			addorupdatelink($pageid,$row['author'],$row['title'],$row['link'],$row['cat'],$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,$row['updatetime']);
			$sqlupdate = "update page set updatetime = '".$row['updatetime']."' where id = '".$pageid."';";
			dh_mysql_query($sqlupdate);
			
			//删除onlylink中的部分
			$sql="delete from onlylink where link='".$row['link']."'";
			$sqlresult=dh_mysql_query($sql);
		}
		else
		{
			echo " no get page set fail ++ ";
			$sqlupdate = "update onlylink set fail = fail+1 where link = '".$row['link']."'" ;
			dh_mysql_query($sqlupdate);	
		}		
	}
}
