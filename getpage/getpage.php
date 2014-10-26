<?php

//利用link修改page的状态

require("../config.php");
require("../common/curl.php");
require("../common/base.php");
require("../common/compare.php");
require("../common/dbaction.php");
require("getsites/douban.php");
require("common.php");

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
	$d=2;
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
	}
	$datebegin = getupdatebegin($d,'link');
	if( isset($_REQUEST['d']))
	{
		$datebegin = getupdatebegin($_REQUEST['d']);
	}		
	$sql="select * from onlylink where updatetime > '$datebegin' and fail < 2 and mtitle is not null";

	if(isset($_REQUEST['id']))
	{
		$pageid = $_REQUEST['id'];
		$sql="select * from link where pageid=$pageid";	
	}
	if(isset($_REQUEST['link']))
	{
		$link = $_REQUEST['link'];
		$sql="select * from link where link='$link'";	
	}	
	
	//$sql.=' limit 0,2 ';
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$count=0;
		while($row = mysql_fetch_array($results))
		{
			$count++;
			//每次需要休息5秒钟
			//if($count > 2)
			//	break;
			sleep(5);
			//先从数据库寻找，再从豆瓣寻找	
			echo "\n\n".$count.": ";
			$maxrate=1;
			
			$sqlauthor="select * from author where name='".$row['author']."'";
			$resultsauthor=dh_mysql_query($sqlauthor);
			$rowauthor = mysql_fetch_array($resultsauthor);	
			if(getlinkmeta($rowauthor,$linkway,$linktype,$linkquality,$linkdownway,$linkvalue,$row['link'],$row['title'],$row['cat'])==-1)
				continue;
				
			$pageid = getdbpageid($row['title'],$row['mtitle'],$row['moviecountry'],$row['movieyear'],$row['movietype'],$maxrate);	
			if($pageid>=0)
			{
				addorupdatelink($pageid,$row['author'],$row['title'],$row['link'],$row['cat'],$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,$row['updatetime']);
				$sqlupdate = "update page set updatetime = '".$row['updatetime']."' where id = '".$pageid."';";
				dh_mysql_query($sqlupdate);
				
				//删除onlylink中的部分
				$sql="delete from onlylink where link='".$row['link']."'";
				$sqlresult=dh_mysql_query($sql);
				continue;
			}
			
			$douban_result = new MovieResult();
			$res = get_douban($row['mtitle'],$row['moviecountry'],$row['movieyear'],$row['movietype'],$douban_result);			
			if($res)
			{
				echo "\n douban last: ".$douban_result->mediaid;
				echo " update $douban_result->title ";
				$mediaid=$douban_result->mediaid;
				//print_r($douban_result);
				updatepage($douban_result,$row['updatetime']);
				//得到更新的page的id
				addorupdatelink(-1,$row['author'],$row['title'],$row['link'],$row['cat'],$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,$row['updatetime']);
				$sqlupdate = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '".$row['link']."'";
				dh_mysql_query($sqlupdate);				
				if($douban_result->doubantrail!='')
				{
					addorupdatelink($pageid,'豆瓣预告',$douban_result->doubantrail,$douban_result->doubantrailurl,'',0,3,7,0,0,$row['updatetime'],1);
					$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->doubantrailurl'" ;
					$sqlresult=dh_mysql_query($sql);
				}
				if($douban_result->doubansp!='')
				{
					addorupdatelink($pageid,'豆瓣播放',$douban_result->doubansp,$douban_result->doubanspurl,'',0,7,7,0,0,$row['updatetime'],1);
					$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->doubanspurl'" ;
					$sqlresult=dh_mysql_query($sql);
				}
				//删除onlylink中的部分
				//$sql="delete from onlylink where link='".$row['link']."'";
				//$sqlresult=dh_mysql_query($sql);				
			}
			else
			{
				echo " no get douban_result set fail ++ ";
				$sqlupdate = "update onlylink set fail = fail+1 where link = '".$row['link']."'" ;
				dh_mysql_query($sqlupdate);	
			}
        }
	}
}
