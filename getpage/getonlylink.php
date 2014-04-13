<?php
require("../config.php");
require("../common/common.php");
require("../common/dbaction.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getlink();
mysql_close($conn);

function getlink()
{
	$sql="select l.*,a.* from onlylink l,author a where l.author=a.name";

	//全部重新计算link
	if(isset($_REQUEST['reget']))
	{
		$sql="select l.*,a.* from link l,author a WHERE l.author = a.name";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['aid']))
	{
		$aid = $_REQUEST['aid'];
		$sql="select l.*,a.* from link l,author a where a.id = $aid and l.author=a.name";
	}	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	$i=0;
	while($row = mysql_fetch_array($results))
	{
		//print_r($row);
		echo $i.":";
		$i++;
		//对linktype不符合的选项，不予处理
		$author=$row['author'];
		$title=$row['title'];
		$link=$row['link'];
		$cat=$row['cat'];
		$updatetime=$row['updatetime'];			
		
		$linkway = testneed($row['clinkway'],$link,$title,$cat);
		if($linkway<0)
		{
			//linktype 不对，说明有问题不大
			echo "linkway=$linkway % title=$title link=$link cat=$cat -> linkway error 失败，请查明原因！</br> \n";
			continue;
		}		

		$mtitle=testtitle($row['ctitle'],$row['title']);
		if($mtitle<0||$mtitle==='')
		{
			//问题很大
			echo "mtitle=$mtitle % title=$title link=$link cat=$cat -> mtitle error 失败，请查明原因！</br> \n";
			continue;
		}			
		
		$linktype = testneed($row['clinktype'],$link,$title,$cat);
		$movietype = testneed($row['cmovietype'],$link,$title,$cat);
		$moviecountry = testneed($row['cmoviecountry'],$link,$title,$cat);
		$linkquality = testneed($row['clinkquality'],$link,$title,$cat);
		$linkdownway = testneed($row['clinkdownway'],$link,$title,$cat);			
		
		$movieyear = 0;
		preg_match('/((19|20|18)[0-9]{2,2})/',$title,$match);
		if(!empty($match[1]))
			$movieyear=$match[1];

		addorupdateonlylink($author,$title,$link,$cat,$updatetime,$linkquality,$linkway,$linktype,$linkdownway,$mtitle,$moviecountry,$movieyear,$movietype);
	}
}