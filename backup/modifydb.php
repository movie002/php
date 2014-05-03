<?php
require("../config.php");

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
//	$sql="SELECT * FROM `page` where m1905id!='' and mtimeid!=''";

	$results=dh_mysql_query($sql);	
	if($results)
	{	
		$i=0;
		while($row = mysql_fetch_array($results))
		{
			//echo $row['simgurl'].'</br>';
			$ids =$row['ids'];
			preg_match('/<r>(.*?)\((.*?)人评价\)<\/r>/s',$row['meta'],$match);
			//print_r($match);
			$meta = $row['meta'];
			if(!empty($match[1]))
			{
				$ids .='<r1>'.$match[1].'('.$match[2].')</r1>';
				$meta = preg_replace('/<r>(.*?)<\/r>/','',$meta);
			}
			if($row['mediaid']!='')
			{
				preg_match('/<1>(.*?)<\/1>/s',$ids,$match1);
				if(empty($match1[1]))			
					$ids .='<1>'.$row['mediaid'].'</1>';
			}
			
			preg_match('/<r1>(.*?)<\/r1>/s',$row['meta'],$match);
			if(!empty($match[1]))
			{
				$ids .='<r3>'.$match[1].'</r3>';
				$meta = preg_replace('/<r1>(.*?)<\/r1>/s','',$meta);
			}
			if($row['mtimeid']!='')
			{
				preg_match('/<3>(.*?)<\/3>/s',$ids,$match1);
				if(empty($match1[1]))
					$ids .='<3>'.$row['mtimeid'].'</3>';
			}
			
			preg_match('/<r2>(.*?)<\/r2>/s',$row['meta'],$match);
			if(!empty($match[1]))
			{
				$ids .='<r2>'.$match[1].'</r2>';
				$meta = preg_replace('/<r2>(.*?)<\/r2>/s','',$meta);
			}
			if($row['m1905id']!='')
			{
				preg_match('/<2>(.*?)<\/2>/s',$ids,$match1);
				if(empty($match1[1]))
					$ids .='<2>'.$row['m1905id'].'</2>';
			}	
			preg_match('/<m>(.*?)<\/m>/s',$row['meta'],$match);
			if(!empty($match[1]))
			{
				$ids .=$match[0];
				$meta = preg_replace('/<m>(.*?)<\/m>/s','',$meta);
			}
			
			echo ' >>> '.$ids."\n    ".$meta."</br>\n";
			$sql="update page set meta = '".$meta."',ids='".$ids."' where id = '".$row['id']."'";
			dh_mysql_query($sql);			
        }
	}
}