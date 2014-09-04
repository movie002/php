<?php 
/* 使用示例 */   

require("../config.php");
require("../common/base.php");
require("../common/curl.php");
require("../common/dbaction.php");
require("getsites/douban.php");
require("getsites/get.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");

//每个礼拜刷新一次
$day=date("w");
//echo 'w:  '.$day;

if( isset($_REQUEST['d']))
{
	$day=$_REQUEST['d'];
}

if(!($day==4 || $day==0))
{
	echo "不做任何动作，退出!</br>\n";
	return;
}
						
nowplaying();
coming();

//处理电影名  
function nowplaying()  
{ 
	$buffer = get_file_curl('http://movie.douban.com/nowplaying/shanghai/');
	if(false===$buffer)
	{
		echo "抓取失败 error! </br>\n";
		return;
	}
	//echo $buffer;	
	
	preg_match_all('/<li class="poster">[\s]+<a href="http:\/\/movie\.douban\.com\/subject\/(.*?)\/\?from=.*?"[^<]+<img src="(.*?)\.jpg" alt="(.*?)".*?\/>[\s]+<\/a>.*?<\/li>/s',$buffer,$match);
	//print_r($match);
	
	if(!empty($match[1][0]))
	{
		$sql="update page set mstatus=0 where mstatus=3;";
		dh_mysql_query($sql);	
		foreach($match[1] as $key=>$eachresult)
		{
			$sql="update page set mstatus=3 where mediaid='".$match[1][$key]."'";
			echo $sql."\n</br>";
			dh_mysql_query($sql);
		}
		
		foreach($match[1] as $key=>$eachresult)
		{
			//if($key>1)
			//	break;
			sleep(10);
			$result = new MovieResult();
			$result->mediaid = $match[1][$key];
			
			//找到page记录
			$sql="select * from page where mediaid='$result->mediaid'";
			$results = dh_mysql_query($sql);
			if($row = mysql_fetch_array($results))
			{
				$result->ids = $row['ids'];
				$result->imgurl = $row['imgurl'];
				$result->simgurl = $row['simgurl'];
				$result->meta = $row['meta'];
				$result->pubdate = $row['pubdate'];
				$result->country = $row['catcountry'];
				$result->summary = $row['summary'];	
				$result->title = $match[3][$key];
				$result->imgurl = getimgurl($match[2][$key]);			
				getdetail($result);		
			}
			else
			{					
				echo "无记录:";							
			}
			$result->imgurl = getimgurl($match[2][$key]);
			print_r($result);			
			updatepage2($result,3);
			getallsites($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		}	
	}
}

function coming()  
{ 
	$buffer = get_file_curl('http://movie.douban.com/coming');
	if(false===$buffer)
	{
		echo "抓取失败 error! </br>\n";
		return;
	}
	//echo $buffer;
	
	preg_match_all('/subject\/([0-9]+)\/" class="">(.*?)<\/a>/s',$buffer,$match);	
	//print_r($match);

	if(!empty($match[1][0]))
	{
		$sql="update page set mstatus=0 where mstatus=2;";
		dh_mysql_query($sql);
		foreach($match[1] as $key=>$eachresult)
		{
			$sql="update page set mstatus=2 where id='".$match[1][$key]."'";
			echo $sql."\n</br>";
			dh_mysql_query($sql);
		}
		foreach($match[1] as $key=>$eachresult)
		{
			//if($key>1)
			//	break;
			sleep(10);
			$result = new MovieResult();
			$result->mediaid = $match[1][$key];	
	
			//找到page记录
			$sql="select * from page where mediaid='$result->mediaid'";
			$results = dh_mysql_query($sql);
			if($row = mysql_fetch_array($results))
			{
				$result->ids = $row['ids'];
				$result->imgurl = $row['imgurl'];
				$result->simgurl = $row['simgurl'];
				$result->meta = $row['meta'];
				$result->pubdate = $row['pubdate'];
				$result->country = $row['catcountry'];
				$result->summary = $row['summary'];
			}
			
			$result->title = $match[2][$key];
			//$result->imgurl = $match[2][$key];
			getdetail($result);
			print_r($result);
			updatepage2($result,2);
			getallsites($row['title'],$row['aka'],$row['cattype'],$row['updatetime'],$row['id']);
		}		
	}
}

function getimgurl($list)
{
	preg_match('/.*?\/public\/p(.*?)$/s',$list,$match);
	if(!empty($match[1]))
	{
		return $match[1];
	}
	else
	{
		preg_match('/.*?\/s(.*?)$/s',$list,$match);
		if(!empty($match[1]))
		{
			return 's'.$match[1].'.jpg';
		}		
	}
	return '';
}
?>  