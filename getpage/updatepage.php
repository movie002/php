<?php 
/* 使用示例 */   

require("../config.php");
require("../common/base.php");
require("../common/curl.php");
require("../common/dbaction.php");
require("./getsites/get.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");

updatepageother();

//处理电影名  
function updatepageother()  
{ 
	$sql="select * from page ";
	$d=2;
	if( isset($_REQUEST['d']))
		$d = $_REQUEST['d'];
	$datebegin = getupdatebegin($d,'onlylink');
	$sql .= " where updatetime > '$datebegin'";
	
	echo $sql."</br>\n";
	$count=0;
	$results=dh_mysql_query($sql);

	while($row = mysql_fetch_array($results))
	{
		$count++;
		if($count!=1)
			sleep(10);
		echo "\n</br>".$count.": ".$row['id'].': '.$row['title'].' --> '.$row['cattype'].'/'.$row['catcountry'].'/'.$row['updatetime']."\n</br>";
		getallsites($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['ids'],$row['id']);
	}
}
?>  
