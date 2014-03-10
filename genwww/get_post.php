<?php
function dh_mysql_query($sql)
{
	$rs = mysql_query($sql);
	$mysql_error = mysql_error();
	if($mysql_error)
	{
		echo 'dh_mysql_query error info:'.$mysql_error.'</br>';
		echo $sql;
		return null;
	}
	return $rs;
}

$dbip='localhost';
$dbuser='root';
$dbpasswd='root';
$dbname='dhblog';

require("config.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getpost();
mysql_close($conn);

function getpost()
{
	$sql="select * from wp_posts where post_type ='post'";
	$results=dh_mysql_query($sql);
	$old='';	
	if($results)
	{			
		while($row = mysql_fetch_array($results))
		{
			$title=$row['post_title'];
			$datewrite = strtotime($row['post_date']);
			$datenow = date("YmdH",$datewrite);
			$body=$row['post_content'];
			$id=$row['ID'];
			$tags='';
			$sql2="select wp_terms.name from wp_term_relationships,wp_terms where wp_term_relationships.object_id=$id and wp_term_relationships.term_taxonomy_id=wp_terms.term_id";			
			$results2=dh_mysql_query($sql2);
			if($results2)
			{
				while($row2 = mysql_fetch_array($results2))
				{
					//echo $row2['name'].':';
					$tags.='<_t>'.$row2['name'].'</_t>';
				}
			}
			
			$aold="<_e><_d>$datenow</_d><_T>$title</_T><_c>建站技术</_c>$tags<_a>DH</_a>
<_b><!--
$body
--></_b>
</_e>";
			$old .= $aold."\n";
        }
	}
	dh_file_put_contents('old.xml',$old);
}