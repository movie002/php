<?php

//利用link修改page的状态

require("../config.php");
require("../curl.php");
require("../common.php");
require("dbpage.php");

require("getsites/douban.php");
require("getsites/mtime.php");
require("getsites/m1905.php");
require("getsites/v360.php");
require("getsites/v2345.php");
require("getsites/vbaidu.php");
require("getsites/www.cili.so.php");
require("getsites/bt.shousibaocai.com.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(7200); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getpage();
mysql_close($conn);

function getpage()
{
	$datebegin = getupdatebegin(2);
//	$sql="select * from link where author ='无忧无虑'";
	$sql="select * from link where (pageid ='' or pageid is null) and updatetime > '$datebegin' and fail < 1";
//	$sql="select * from link where pageid ='' and ctitle<>'' and linktype=3";

	if(isset($_REQUEST['pageid']))
	{
		$pageid = $_REQUEST['pageid'];
		$sql="select * from link where pageid=$pageid";	
	}
	if(isset($_REQUEST['link']))
	{
		$link = $_REQUEST['link'];
		$sql="select * from link where link='$link'";	
	}	
	
	echo $sql;
	
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$count=0;
		while($row = mysql_fetch_array($results))
		{
			//每次需要休息5秒钟
			//if($count > 2)
			//	break;
			sleep(5);
			$douban_result = new MovieResult();
			echo "\n \n".$count.':'.$row['link'].' '.$row['ctitle'].' ';
			$serach_input = new SearchInput();
			$serach_input->title=$row['ctitle'];
			$serach_input->country=$row['moviecountry'];
			$serach_input->year=$row['movieyear'];
			$serach_input->type=$row['movietype'];
			//先从数据库寻找，再从豆瓣寻找
			$maxrate=1;
			$pageid = getdbpageid($serach_input,$row['link'],$maxrate);
			if($pageid>=0)
			{
				$sqlupdate = "update link set pageid = ". $pageid.", lstatus=".$maxrate." where link = '".$row['link']."'" ;
				dh_mysql_query($sqlupdate);
				$sqlupdate = "update page set updatetime = '".$row['updatetime']."' where id = '".$pageid."';";
				dh_mysql_query($sqlupdate);
				
				$sqlupdate = "select * from page where id = '".$pageid."';";
				$result_sqlupdate = dh_mysql_query($sqlupdate);
				$row_sqlupdate = mysql_fetch_array($result_sqlupdate);
				
				
				$douban_result->title = $row_sqlupdate['title'];
				$douban_result->aka = $row_sqlupdate['aka'];
				$douban_result->updatetime = $row_sqlupdate['updatetime'];
				$douban_result->type = $row_sqlupdate['cattype'];
				
				get_v360($douban_result,$pageid);
				get_v2345($douban_result,$pageid);
				get_vbaidu($douban_result,$pageid);
				get_cili($douban_result,$pageid);
				get_shousibaocai($douban_result,$pageid);				
				continue;
			}
			
			$res = get_douban($serach_input,$douban_result);			
			if($res)
			{
				echo "\n douban last: ".$douban_result->mediaid;
				echo " update $douban_result->title ";
				
				if($douban_result->type===1)
				{
					//$input = new SearchInput();
					//$input->title=$douban_result->title;
					//$input->year = $douban_result->pubdate;
					get_m1905($douban_result);
					get_mtime($douban_result);
				}
				//print_r($douban_result);
				updatepage($douban_result,$row['updatetime']);
				insertpagelink($douban_result,$row['updatetime'],$douban_result->mediaid);
				//得到更新的page的id
				$sqlupdate = "update link set pageid = (select id from page where mediaid='".$douban_result->mediaid."') where link = '".$row['link']."'";
				dh_mysql_query($sqlupdate);
				
				$douban_result->updatetime = $row['updatetime'];				
				get_v360($douban_result);
				get_v2345($douban_result);
				get_vbaidu($douban_result);
				get_cili($douban_result);
				get_shousibaocai($douban_result);
				
				//插入到cele表中
				preg_match("/<c>(.*?)<\/c>/",$douban_result->meta,$match);
				if(!empty($match[1]))
				{
					insertcelebrity($match[1]);
				}
				preg_match("/<d>(.*?)<\/d>/",$douban_result->meta,$match);
				if(!empty($match[1]))
				{
					insertcelebrity($match[1]);
				}				
			}
			else
			{
				echo " no get douban_result set fail ++ ";
				$sqlupdate = "update link set fail = fail+1 where link = '".$row['link']."'" ;
				dh_mysql_query($sqlupdate);	
			}
			echo "\n";
			$count ++;
        }
	}
}
