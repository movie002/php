<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_author
/// 函数作用：产生静态的最新的各个网站的文章数目列表页面
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
set_time_limit(1200);

#需要使用的基础函数
include("../config.php");
include("config.php");
include("../share/share.php");
include("../common/base.php");
include("../common/compressJS.class.php");
include("../common/dbaction.php");
include("../share/tongji/author.php");
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$DH_author_path=$DH_output_path;

dh_gen_author();

mysql_close($conn);
?>
