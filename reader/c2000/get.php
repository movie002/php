<?php
require("../../config.php");
require("../../common.php");
require("../../curl.php");
require("../../gen/common.php");

require("news.mtime.com.php");
require("www.mtime.com.review.php");
require("www.hunantv.com.php");
require("ent.ifeng.com.php");
require("ent.163.com.php");
require("ent.sina.com.cn.php");
require("yule.sohu.com.php");


header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

reader();
mysql_close($conn);

class author
{
	var $id;
	var $name;	
	var $clinktype;
	var $ctitle;
	var $updatetime;
	var $rssurl;
	var $rss;
}

function reader()
{
	$sql="select * from author where rss >=2000 and rss <3000";
	$results=dh_mysql_query($sql);
	if($results)
	{
		$author = new author();
		while($row = mysql_fetch_array($results))
		{
			//打开rss地址，并读取，读取失败则中止
			echo $row['name']."的全部文章:</br>\n";
			$author->id=$row['id'];
			$author->name=$row['name'];
			$author->clinktype=$row['clinktype'];
			$author->ctitle=$row['ctitle'];
			$author->updatetime=$row['updatetime'];
			$author->rssurl=$row['rssurl'];
			$author->rss=$row['rss'];
			print_r($author);		
			readrssfile2001($author);
			readrssfile2002($author);
			readrssfile2003($author);
			readrssfile2004($author);
			readrssfile2005($author);			
			readrssfile2006($author);
			readrssfile2007($author);
        }
	}
}
?>