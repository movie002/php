<?php 
/* 使用示例 */   

require("../config.php");
require("../common/base.php");
require("../common/curl.php");
require("../common/dbaction.php");
require("getsites/v360.php");
require("getsites/v2345.php");
require("getsites/vbaidu.php");
require("getsites/www.cili.so.php");
require("getsites/bt.shousibaocai.com.php");
require("getsites/yugaopian.com.php");

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
	$sql="select * from page limit 0,2";
	//$d=2;
	//if( isset($_REQUEST['d']))
	//	$d = $_REQUEST['d'];
	//$datebegin = getupdatebegin($d,'onlylink');
	//$sql .= " where updatetime > '$datebegin'";
	//
	echo $sql."</br>\n";
	$count=0;
	$results=dh_mysql_query($sql);

	while($row = mysql_fetch_array($results))
	{
		$count++;
		echo "\n".$count.": ".$row['id'].': '.$row['title'].' --> '.$row['cattype'].'/'.$row['catcountry'].'/'.$row['updatetime'];
		$title=
		//get_v360($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		//get_v2345($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		get_vbaidu($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		//get_cili($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		//get_shousibaocai($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		//get_yugaopian($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);	
	}
}
?>  