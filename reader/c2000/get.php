<?php
require("../../config.php");
require("../../common/base.php");
require("../../common/curl.php");
require("../../common/dbaction.php");


function getupdatetime($urlcat,$authorname)
{
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$authorname' and cat like '%".$eachurlcat."%'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
	return $updatetime;
}

function geturl($trueurl,$authorname)
{
	$buff = get_file_curl($trueurl);
	//如果失败，就使用就标记失败次数
	if(!$buff)
	{
		sleep(5);
		$buff = get_file_curl($trueurl);
		if(!$buff)
		{
			echo 'error: fail to get file '.$trueurl."!</br>\n";	
			$sql="update author set failtimes=failtimes+1 where name='$authorname';";
			$result=dh_mysql_query($sql);
			return false;
		}
	}
	return $buff;
}
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


header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

//news_mtime_com_php();
//www_mtime_com_review_php();
www_mtime_com_trailer_php();
//www_hunantv_com_php();
//ent_ifeng_com_php();
//ent_163_com_php();
//ent_sina_com_cn_review_php();
//yule_sohu_com_php();
//www_gewara_com_news_php();
//www_gewara_com_activity_php();

mysql_close($conn);
?>