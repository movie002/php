<?php
//利用link2修改page的状态

require("../config.php");
require("../common/curl.php");
require("../common/common.php");
require("dbpage.php");
require("getsites/douban.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getpage();
mysql_close($conn);

function getpage()
{
	$datebegin = getupdatebegin(60);
//	$sql="select * from link2 where link ='http://www.yyets.com/resource/29582'";
	$sql="select * from link2 where (pageid ='' or pageid is NULL) and updatetime > '$datebegin'";
//	$sql="select * from link2 where pageid ='' and ctitle<>'' and linktype=3";	
	echo $sql;
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$count=0;
		while($row = mysql_fetch_array($results))
		{
			echo "\n \n".$count.':'.$row['link'].' ';
			$serach_input = new SearchInput();
			$serach_input->title=$row['ctitle'];
			$serach_input->country=$row['moviecountry'];
			$serach_input->year=$row['movieyear'];
			$serach_input->type=$row['movietype'];
			$maxrate=0.1;
			$pageid = getdbpageid($serach_input,$row['link'],$maxrate);
			if($pageid>=0)
			{
				$sqlupdate = "update link2 set pageid = ". $pageid.", lstatus=".$maxrate." where link = '".$row['link']."'" ;
			}
			else
			{				
				echo " no get page result set fail ++ ";
				$sqlupdate = "update link2 set fail = fail+1 where link = '".$row['link']."'" ;
			}
			dh_mysql_query($sqlupdate);
			$count ++;
        }
	}
}
