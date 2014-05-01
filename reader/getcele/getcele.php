<?php
require("../../config.php");
require("../../common/curl.php");
require("../../common/base.php");
require("../../common/dbaction.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(9999); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getcele();
mysql_close($conn);

function getcele()
{
	$sql="select id from celebrity where (pic='' or pic is null) and id <>'' and fail < 3";
	$results=dh_mysql_query($sql);	
	if($results)
	{	
		$i = 0;
		while($row = mysql_fetch_array($results))
		{
			$i++;
			//if($i>2)
			//	break;
			sleep(5);
			echo $row['id']."<br/>\n";
			$buffer ='';
			if($row['id']!='')
			{
				$buffer = get_file_curl("http://movie.douban.com/celebrity/".$row['id']."/");
				if(false===$buffer)
				{
					$sql="update celebrity set fail = fail+1 where id = '".$row['id']."'";
					dh_mysql_query($sql);
					echo "影人信息读取失败\n";				
					continue;
				}
				//print_r($buffer);
				preg_match('/douban.com\/img\/celebrity\/large\/(.*?)">/',$buffer,$match);
				print_r($match);
				if(!empty($match[1]))
				{
					echo $match[1];
					echo "<br/>\n";
					$sql="update celebrity set pic = '". $match[1]."' where id = '".$row['id']."'";
					dh_mysql_query($sql);
				}
				else
				{
					$sql="update celebrity set fail = fail+1 where id = '".$row['id']."'";
					dh_mysql_query($sql);
					echo "影人信息读取失败\n";						
				}
			}	
			//print_r($buffer);
        }
	}
}