<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_list
/// 函数作用：产生静态的index.html页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
require("../config.php");
require("config.php");
require("../common/base.php");
require("../common/dbaction.php");
require("../common/compressJS.class.php");
require("../share/share.php");

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

dh_gen_search($DH_home_url,"sm.php");
?>
