<?php
require("../config.php");
require("../common/base.php");
require("../common/dbaction.php");
require("x11.php");
require("common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getonlylink();
mysql_close($conn);

function getonlylink()
{
	$sql="select l.*,a.* from onlylink l,author a where l.author=a.name and l.fail<3 ";
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
		$datebegin = getupdatebegin($d,'onlylink');
		$sql .= " and l.updatetime > '$datebegin'";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['a']))
	{
		$a = $_REQUEST['a'];
		$sql .= " and a.name = '$a'";
	}
	if(!isset($_REQUEST['all']))
	{
		$sql .= " and mtitle is null";
	}
	
	//$sql .=' limit 0,200';
	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	$i=0;
	while($row = mysql_fetch_array($results))
	{
		//print_r($row);
		echo "\n".$i.":";
		$i++;
		if(getlinkmeta($row,$linkway,$linktype,$linkquality,$linkdownway,$linkvalue,$row['link'],$row['title'],$row['cat'])==-1)
        {
            echo 'get linkmeta error!</br>';
            setfail($row);
            continue; 
        }
		echo "\n".$row['title'];
		if($movieall = getmoviemeta($row,$mtitle,$moviecountry,$movieyear,$movietype,$row['link'],$row['title'],$row['cat'])==-1)
		{
			echo "movietype and country and year not right % cmovietype=".$row['cmovietype']." cmoviecountry=".$row['cmoviecountry']." mtitle=".$mtitle." ->  error 失败，请查明原因！</br> \n";
			setfail($row);
			continue;
		}
		echo "\n -->".$mtitle;
		updateonlylink($row['author'],$row['title'],$row['link'],$row['cat'],$row['updatetime'],$mtitle,$moviecountry,$movieyear,$movietype);
	}
}

function setfail($row)
{
	echo " no get onlylink set fail ++ ";
	$sqlupdate = "update onlylink set fail = fail+1 where link = '".$row['link']."'" ;
	dh_mysql_query($sqlupdate);	
}
?>
