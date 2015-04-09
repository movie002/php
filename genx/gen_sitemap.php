<?php
/////////////////////////////////////////////////////
/// 函数名称： 
/// 函数作用：
/// 函数作者: DH
/// 作者地址: http://movie002.com/ 
/////////////////////////////////////////////////////
header('Content-Type:text/html;charset= UTF-8'); 

//导出数据库设置
require("../config.php");
require("config.php");
require("../common/base.php");
require("../common/dbaction.php");
require("../common/page_navi.php");
require("../share/sitemap/function.php");

$DH_sitemap_input_path=$DH_input_path."share/";

function dh_pagenum_link($link,$page)
{
	return $link.sprintf("%03d",$page).'.html';
}

set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

//一页网页地图使用多少个项
//资料：Google允许的sitemap数量是1000，现在提升到50000，但是这是理论数据。根据测试，实际情况所对应的以下数据为佳。
//调整前：理论最大1000条URL，实际500以下为佳，
//调整后：理论最大50000条URL，实际2500以下为佳。
$pagecount=2500;
//向前多生成几页
$pregen=3;

if(isset($_REQUEST['pre']))
{
	$pregen = $_REQUEST['pre'];
}

//最大的id号
$sql="select max(id) from page";
$results=dh_mysql_query($sql);			
$maxid = mysql_fetch_array($results);

$times=ceil($maxid[0]/$pagecount);
$i=$times;
$j=$times-$pregen;
$begin=$j>0?$j:0;
echo $times."--> ";
echo $begin."\n";

while($i>$begin)
{
	echo $i."\n";
	echo $pagecount*($i).'-->'.$pagecount*($i-1)."\n";
	$sql="select * from page where id>".$pagecount*($i-1)." and id <=".$pagecount*$i." order by id desc";
	echo $sql."\n";
	genhtml($sql,sprintf("%03d",$i),$times);
	gen_sitemap($sql,date("Y-m-d H:i:s"),'weekly',sprintf("%03d",$i),sprintf("%03d",$times));
	$i--;
}

//按月生成每个sitemap
genhtml2();
gen_siteindex(date("Y-m-d H:i:s"));
gen_sitemapall();

?>
