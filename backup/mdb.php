<?php
require("../config.php");
require("../common/dbaction.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(9999); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
modifydb();
mysql_close($conn);

function modifydb()
{
	global $conn;
	$sql="select * from page";
//	$sql="select * from page where updatetime > '2014-01-01'";
//	$sql="SELECT * FROM `page` where m1905id!='' and mtimeid!=''";

	$results=dh_mysql_query($sql);	
	if($results)
	{	
		$i=0;
		while($row = mysql_fetch_array($results))
		{
			//echo $row['simgurl'].'</br>';
			preg_match('/<g>(.*?)<\/g>/s',$row['meta'],$match);
			//print_r($match);
			if(!empty($match[1]))
			{
				$country =$match[1];
				$res = get_moviecountry($country);
				echo ' >>> '.$match[1];
			}
			else
				$res = 4;
			echo " --> ".$res."</br>\n";
			$sql="update page set catcountry =$res where id = '".$row['id']."'";
			dh_mysql_query($sql);
        }
	}
}