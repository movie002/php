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

if($id==0||$id==1)echo "id: 1   ";news_mtime_com_php();
if($id==0||$id==2)echo "id: 2   ";www_mtime_com_review_php();
if($id==0||$id==3)echo "id: 3   ";www_mtime_com_trailer_php();
if($id==0||$id==4)echo "id: 4   ";www_hunantv_com_php();
if($id==0||$id==5)echo "id: 5   ";ent_ifeng_com_php();
if($id==0||$id==6)echo "id: 6   ";ent_163_com_php();
if($id==0||$id==7)echo "id: 7   ";ent_sina_com_cn_review_php();
if($id==0||$id==8)echo "id: 8   ";yule_sohu_com_php();
if($id==0||$id==9)echo "id: 9   ";www_gewara_com_news_php();
if($id==0||$id==10)echo "id: 10   ";www_gewara_com_activity_php();
if($id==0||$id==11)echo "id: 11   ";www_sfscn_com_php();

mysql_close($conn);
?>