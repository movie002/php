<?php 

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/base.php");
//require("../../common/dbaction.php");

$getsiteurl='./getsites';
require("$getsiteurl/v360.php");
require("$getsiteurl/v2345.php");
require("$getsiteurl/vbaidu.php");
require("$getsiteurl/bt.shousibaocai.com.php");
require("$getsiteurl/yugaopian.com.php");
require("$getsiteurl/gewara.php");
require("$getsiteurl/wangpiao.php");
require("$getsiteurl/mtime.php");
require("$getsiteurl/m1905.php");


//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//getallsites('催眠大师','催眠大师',1,'2000-05-05 00:00:00','',4);
//mysql_close($conn);

function getallsites($title,$aka,$cattype,$updatetime,$ids,$pageid)
{
	get_m1905($title,$aka,$cattype,$updatetime,$ids,$pageid);
	get_mtime($title,$aka,$cattype,$updatetime,$ids,$pageid);
	get_v360($title,$aka,$cattype,$updatetime,$pageid);
	get_v2345($title,$aka,$cattype,$updatetime,$pageid);
	//get_vbaidu($title,$aka,$cattype,$updatetime,$pageid);
	//get_shousibaocai($title,$aka,$cattype,$updatetime,$pageid);
	get_yugaopian($title,$aka,$cattype,$updatetime,$pageid);	
	get_gewara($title,$aka,$cattype,$updatetime,$pageid);	
}
?>  
