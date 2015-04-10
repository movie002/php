<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_update 
/// 函数作用：产生每天和每个礼拜的更新内容
/// 函数作者: DH
/// 作者地址: http://movie002.com 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set("Asia/Shanghai");//设定时区东八区

require("../config.php");
require("config.php");
#需要使用的基础函数
require("../common/base.php");
require("../share/share.php");
require("../common/dbaction.php");
require("../mail/mail.php");
require("../common/compressJS.class.php");
include("../share/tongji/update.php");

set_time_limit(600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

//检查是否需要做，如果今天做过了，就不做了
$sql="select max(updatetime) from pagetmp where cycle = 1";
$result=dh_mysql_query($sql);
$row = mysql_fetch_array($result);
$dodate=date('Y-m-d 00:00:00',strtotime($row[0]));
$todaydate=date('Y-m-d 00:00:00',time());
echo 'dodate:'.$dodate."\n";
echo 'todaydate:'.$todaydate."\n";
if($dodate>=$todaydate)
	return;
	
dh_gen_update(1);
dh_save(1);

//echo output_page_path(63);

?>
