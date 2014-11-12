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

news_mtime_com_php();
www_mtime_com_review_php();
www_mtime_com_trailer_php();
www_hunantv_com_php();
ent_ifeng_com_php();
ent_163_com_php();
ent_sina_com_cn_review_php();
yule_sohu_com_php();
www_gewara_com_news_php();
www_gewara_com_activity_php();
www_sfscn_com_php();

mysql_close($conn);
?>