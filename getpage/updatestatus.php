<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生静态的页面的html页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
require("../config.php");
#需要使用的基础函数
require("../common/dbaction.php");
require("../common/base.php");
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$daybeginnum=30;
if( isset($_REQUEST['d']))
{
	$daybeginnum = $_REQUEST['d'];
}
	
$daybegin = getupdatebegin($daybeginnum);
update_status($daybegin);
update_hot($daybegin);
mysql_close($conn);

function update_status($daybegin)
{	
	//得到电影的权重
	$sql="select * from page p where p.updatetime >= '$daybegin'";
	echo "</br>\n".$sql."</br>\n";
	$results=dh_mysql_query($sql);
	if($results)
	{	
		while($row = mysql_fetch_array($results))
		{	
			$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and (l.linkway=6 or l.linkway=7)";
			$results1=dh_mysql_query($sql1);
			$ziyuanall = mysql_fetch_array($results1);
			
			$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=3";
			$results1=dh_mysql_query($sql1);
			$yugaoall = mysql_fetch_array($results1);
            		
			$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=1";
			$results1=dh_mysql_query($sql1);
			$zixunall = mysql_fetch_array($results1);	
            			
			$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=2";
			$results1=dh_mysql_query($sql1);
			$yingpingall = mysql_fetch_array($results1);
			
			$sql1="select max(linkquality) from link l where l.pageid='". $row['id'] ."'";
			$results1=dh_mysql_query($sql1);
			$quality = mysql_fetch_array($results1);			
			//print_r($quality);
			
			if(empty($quality[0]))
				$qualityx=0;
			else
				$qualityx=$quality[0];
				
			$sqlpage="update page set ziyuan=$ziyuanall[0],yugao=$yugaoall[0],zixun=$zixunall[0],yingping=$yingpingall[0],quality=$qualityx where id = '".$row['id']."'";
			echo $sqlpage."\n";
			dh_mysql_query($sqlpage);
			//如果没有资源有预告，则说明影片是预告状态
			if($ziyuanall[0]==0 && $yugaoall[0]>0)
			{
				$sqlpage="update page set mstatus=1 where id = ".$row['id'];
				dh_mysql_query($sqlpage);
			}
		}
	}	
}

function update_hot($daybegin)
{
	//首先全部清空为0
	$sql="update page p set hot=0 where true";
	$results=dh_mysql_query($sql);	

	$sql="select id,title,ids from page p where DATE_SUB(CURDATE(), INTERVAL 2 YEAR)<=date(pubdate) and p.updatetime >= '$daybegin'";
	$daysbegin = getupdatebegin(7);
	$sqldayslink=" and l.updatetime >= '$daysbegin'";	
	echo "</br>\n".$sql."</br>\n";
	$results=dh_mysql_query($sql);
	if($results)
	{	
		while($row = mysql_fetch_array($results))
		{			
			//权重的计算方法：avg(豆瓣评分+mtime评分+m1905评分)+(ziyuan+yugao)/10+近两个礼拜ziyuan/5+quality
			//将各个网站的比分相加
			echo "\n</br>".$row['title'].':';
			$match='';
			$ratenum=0;
			$hot=0;	
			//豆瓣
			echo "(";
			preg_match('/<r1>(.*?)\(.*?\)<\/r1>/',$row['ids'],$match);
			if(!empty($match[1]))
			{
				echo $match[1]." ";
				$ratenum++;
				$hot +=$match[1];
			}
			//m1905
			preg_match('/<r2>(.*?)\(.*?\)<\/r2>/',$row['ids'],$match);
			if(!empty($match[1]))
			{
				echo $match[1]."  ";
				$ratenum++;
				$hot +=$match[1];
			}
			//mtime
			preg_match('/<r3>(.*?)\(.*?\)<\/r3>/',$row['ids'],$match);
			if(!empty($match[1]))
			{
				echo $match[1]."  ";
				$ratenum++;
				$hot +=$match[1];
			}
			echo ")";			
			if($ratenum!=0)
				$hot = floor(($hot*10)/$ratenum)/10;
			
			//$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway>=6".$sqldayslink;
			//$results1=dh_mysql_query($sql1);
			//$ziyuan = mysql_fetch_array($results1);
			//
			//$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=3".$sqldayslink;
			//$results1=dh_mysql_query($sql1);
			//$yugao = mysql_fetch_array($results1);
            //
			//$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=1".$sqldayslink;
			//$results1=dh_mysql_query($sql1);
			//$zixun = mysql_fetch_array($results1);			
            //
			//$sql1="select count(*) from link l where l.pageid='". $row['id'] ."' and l.linkway=2".$sqldayslink;
			//$results1=dh_mysql_query($sql1);
			//$yingping = mysql_fetch_array($results1);	
			
			//echo '-->'.$hot.' + ('.$ziyuan[0].'+'.$yugao[0].') + ('.$zixun[0].'+'.$yingping[0].')/2-->';						
			//$hot = $hot + ($ziyuan[0]+$yugao[0])+($zixun[0]+$yingping[0])/2;
			
			$sql1="select count(linkvalue) from link l where l.pageid='". $row['id'] ."' ".$sqldayslink;
			$results1=dh_mysql_query($sql1);
			$hotall = mysql_fetch_array($results1);	
			echo '+'.$hotall[0].'=';
			$hot+=$hotall[0];
			echo $hot."</br>\n";
			$sqlpage="update page set hot=$hot where id = ".$row['id'];
			echo $sqlpage;
			dh_mysql_query($sqlpage);
		}
	}	
}
?>
