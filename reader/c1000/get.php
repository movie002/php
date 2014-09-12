<?php
require("../../config.php");
require("../../common/base.php");
require("../../common/curl.php");
require("../../common/dbaction.php");


require("bbs.1lou.com.php");
require("www.5281520.com.php");
require("www.bt5156.com.php");
require("www.bttiantang.com.php");
require("www.dy2018.com.php");
require("www.ed2000.com.php");
require("www.etdown.net.php");
require("www.piaohua.com.php");
//require("www.somag.net.php");
require("www.mp4ba.com.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

//bbs_1ou_com_php();
www_5281520_com_php();
www_bt5156_com_php();
www_bttiantang_com_php();
www_dy2018_com_php();
www_ed2000_com_php();
www_etdown_net_php();
www_piaohua_com_php();
www_mp4ba_com_php();

mysql_close($conn);
?>