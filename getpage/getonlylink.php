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
	$sql="select l.*,a.* from onlylink l,author a where l.author=a.name";
	if( isset($_REQUEST['d']))
	{
		$d=2;
		$d = $_REQUEST['d'];
		$datebegin = getupdatebegin($d);
		$sql .= " and l.updatetime > '$datebegin'";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['a']))
	{
		$a = $_REQUEST['a'];
		$sql .= " and a.name = '$a'";
	}
	if(!isset($_REQUEST['all']))
	{
		$sql .= " and mtitle is null";
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
		if(getlinkmeta($row,$linkway,$linktype,$linkquality,$linkdownway,$row['link'],$row['title'],$row['cat'])==-1)
			continue;
		if(getmoviemeta($row,$mtitle,$moviecountry,$movieyear,$movietype,$link,$title,$cat)==-1)
			continue;
		addorupdateonlylink($author,$title,$link,$cat,$updatetime,$mtitle,$moviecountry,$movieyear,$movietype);
	}
}
?>
