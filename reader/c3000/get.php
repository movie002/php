<?php
require("../../config.php");
require("../../common/base.php");
require("../../common/curl.php");
require("../../common/dbaction.php");

require("news.mtime.com.php");
require("www.mtime.com.review.php");
require("www.mtime.com.trailer.php");
require("www.hunantv.com.php");
require("ent.ifeng.com.php");
require("ent.163.com.php");
require("ent.sina.com.cn.review.php");
require("yule.sohu.com.php");
require("www.gewara.com.news.php");
require("www.gewara.com.activity.php");
require("www.sfs-cn.com.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$id=0;
if(isset($_REQUEST['id']))
	$id = $_REQUEST['id'];

if($id==0||$id==1){echo "id: 1   ";news_mtime_com_php();}

mysql_close($conn);
?>