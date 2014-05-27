<?php
require("../config.php");
require("../common/base.php");
require("../common/dbaction.php");
require("x11.php");
require("common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getonlylink();
mysql_close($conn);

function getonlylink()
{
	$d=2;
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
	}
	$datebegin = getupdatebegin($d);
	$sql="select l.*,a.* from onlylink l,author a where l.updatetime > '$datebegin' and l.author=a.name and mtitle is null";

	//全部重新计算link
	if(isset($_REQUEST['reget']))
	{
		$sql="select l.*,a.* from onlylink l,author a WHERE l.author = a.name";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['aid']))
	{
		$aid = $_REQUEST['aid'];
		$sql="select l.*,a.* from onlylink l,author a where a.id = $aid and l.author=a.name";
	}	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	$i=0;
	while($row = mysql_fetch_array($results))
	{
		//print_r($row);
		echo "\n".$i.":";
		$i++;
		//对linktype不符合的选项，不予处理
		$author=$row['author'];
		$title=$row['title'];
		$link=$row['link'];
		$cat=$row['cat'];
		$updatetime=$row['updatetime'];			
		
		if(getmoviemeta($row,$mtitle,$moviecountry,$movieyear,$movietype,$link,$title,$cat)==-1)
			continue;
		addorupdateonlylink($author,$title,$link,$cat,$updatetime,$mtitle,$moviecountry,$movieyear,$movietype);
	}
}
?>
