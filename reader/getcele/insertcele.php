<?php
require("../../config.php");
require("../../common/base.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(9999); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
insertcele();
mysql_close($conn);

function insertcele()
{
	$sql="select meta from  page;";
	$results=dh_mysql_query($sql);	
	if($results)
	{	
		$i=0;
		
		while($row = mysql_fetch_array($results))
		{
			//echo $row['simgurl'].'</br>';
			preg_match('/<d>(.*?)<\/d>/',$row['meta'],$match);
			//print_r($match);
			if(!empty($match[1]))
				insertcelebrity($match[1]);
			preg_match('/<c>(.*?)<\/c>/',$row['meta'],$match);
			if(!empty($match[1]))
				insertcelebrity($match[1]);				
        }
	}
}
?>