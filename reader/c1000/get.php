<?php
require("../../config.php");
require("../../common.php");
require("../../curl.php");
require("../../gen/common.php");

require("www.bt5156.com.php");
require("www.gaoqing.tv.php");
require("www.ed2000.com.php");
require("www.piaohua.com.php");
require("www.yyets.com.php");
require("www.somag.net.php");
require("henbt.com.php");
//require("www.bestxl.com.php");
require("www.dy2018.com.php");
require("www.bttiantang.com.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

reader();
readrssfile1008();
//readrssfile1002();
readrssfile1006();
readrssfile1009();
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
	$sql="select * from author where rss >=1000 and rss <2000";
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
			readrssfile1001($author);
			readrssfile1003($author);
			readrssfile1004($author);
			readrssfile1005($author);
			readrssfile1007($author);		
			readrssfile1011($author);
        }
	}
}
?>