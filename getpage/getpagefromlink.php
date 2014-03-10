<?php
require("../../config.php");
require("../common/common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
modifylink();
mysql_close($conn);

function modifylink()
{
	$sql="select link.link,link.title,link.cat,author.cmovietype,author.cmoviecountry,author.clinkquality,author.clinktype,author.clinkway,author.clinkonlinetype,author.clinkdowntype,author.contain from link,author where link.author=author.name";
//	$sql="select link.link,link.title,link.cat,author.cmovietype,author.cmoviecountry,author.clinkquality,author.clinktype,author.clinkway,author.clinkonlinetype,author.clinkdowntype,author.contain from link,author where link.author=author.name and link.lstatus=0;";	
	$results=dh_mysql_query($sql);	
	if($results)
	{	
		echo 'movietype moviecountry linkquality linkway linkonlinetype linkdowntype title link'."</br>\n";
		while($row = mysql_fetch_array($results))
		{
			$movietype = testneed($row['cmovietype'],$row['link'],$row['title'],$row['cat']);
			$moviecountry = testneed($row['cmoviecountry'],$row['link'],$row['title'],$row['cat']);
			$linkquality = testneed($row['clinkquality'],$row['link'],$row['title'],$row['cat']);
			$linkway = testneed($row['clinkway'],$row['link'],$row['title'],$row['cat']);
			$linkonlinetype = testneed($row['clinkonlinetype'],$row['link'],$row['title'],$row['cat']);
			$linkdowntype = testneed($row['clinkdowntype'],$row['link'],$row['title'],$row['cat']);
			
			$result = new FilterResult();
			if($row['contain']=='')
			{
				$result = filte_movie_name($result,$row['title']);		
			}
			else
			{
				$result = get_movie_name($result,$row['title'],$row['contain']);
			}
			$link = $row['link'];
			$sql="update link set movietype=$movietype,moviecountry=$moviecountry, linkquality=$linkquality,linkway=$linkway,linkonlinetype=$linkonlinetype, linkdowntype=$linkdowntype,linkdowntype=$linkdowntype,ctitle=\"$result->end_name\", movieyear=$result->year,lstatus=1 where link='$link';";
			$sqlresult=dh_mysql_query($sql);	
			echo $movietype.' '.$moviecountry.' '.$linkquality.' '.$linkway.' '.$linkonlinetype.' '.$linkdowntype.' '.$row['title'].' '.$link.' '.$row['cat']." -> 修改link成功！</br> \n";
        }
	}
}