<?php
require("../config.php");
require("../common/curl.php");
require("../common/base.php");
require("../common/dbaction.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");
all();
mysql_close($conn);

function all()
{
	$sql="select * from author where rss = 1;";
	
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$sql="select * from author where rss = 1 and id=$id;";	
	}
	if(isset($_REQUEST['fid']))
	{
		$fid = $_REQUEST['fid'];
		$lid = $_REQUEST['lid'];
		$sql="select * from author where rss = 1 and id between $fid and $lid;";	
	}
	echo $sql."</br>\n";
	$results=dh_mysql_query($sql);
	if($results)
	{
		$rssinfo = new rssinfo();
		while($row = mysql_fetch_array($results))
		{
			//打开rss地址，并读取，读取失败则中止
			echo "\n".$row['id'].' -- '.$row['name'].'的全部文章:'.$row['rssurl']."</br>\n";
			$rssinfo->author=$row['name'];
			$lastupdate=$row['updatetime'];			
			$buff = geturl($row['rssurl'],$row['name']);
			//$buff = geturljsjump($row['url'],$row['rssurl'],$row['name'],0);
			//print_r($buff);
			if(!$buff)
				continue;
				
			//开始处理得到的结果
			pregrssfile($buff,$rssinfo,$row['url'],$lastupdate);
        }
	}
}

function pregrssfile($buff,$rssinfo,$url,$lastupdate)
{
	$buff =iconvbuff($buff);
	$buff = preg_replace('/encoding=".*?"/','encoding="UTF-8"',$buff);
	//echo $buff;
	//查找所有的item
	preg_match_all('/<item.*?>([\s\S]*?)<\/item>/is',$buff,$match);
	//print_r($match);
	if(empty($match[1]))
	{
		echo 'error: no get item result!';
		return;		
	}
	
	$change = false;
	foreach ($match[1] as $matcheach)
	{
		preg_match('/<pubDate>(.*?)<\/pubDate>/i',$matcheach,$match2);
		//print_r($match2);
		if(empty($match2[1]))
		{
			echo 'error: no get pubDate result!';
			return;		
		}
		$rssinfo->update = date("Y-m-d H:i:s",strtotime(getrealtime(getrealname($match2[1]))));
		//echo $rssinfo->update;
		if($rssinfo->update<$lastupdate)
		{
			echo "爬取到已经爬取文章，爬取结束! </br>\n";
			break;
			//continue;
		}
		$change = true;

		$rssinfo->title='';
		$rssinfo->link='';
		$rssinfo->cat='';
		
		preg_match('/<title>(.*?)<\/title>/is',$matcheach,$match2);
		//print_r($match2);
		if(empty($match2[1]))
		{
			echo 'error: no get title result!';
			return;		
		}
		$rssinfo->title = getrealname($match2[1]);

		preg_match('/<link>(.*?)<\/link>/is',$matcheach,$match2);
		//print_r($match2);
		if(empty($match2[1]))
		{
			echo 'error: no get link result!';
			return;		
		}
		$rssinfo->link = getrealname($match2[1]);
		//对个别网站link不写网站域名的修正
		if(strstr($rssinfo->link,'http')===false)
			$rssinfo->link=$url.$rssinfo->link;
		
		preg_match_all('/<category>(.*?)<\/category>/is',$matcheach,$match2);
		//print_r($match2);
		if(empty($match2[1]))
		{
			//echo 'error: no get category result!';	
		}
		else
		{
			foreach($match2[1] as $key=> $eachcat)
				$rssinfo->cat .=' '.getrealname($eachcat);
		}
		
		preg_match_all('/<categoryname>(.*?)<\/categoryname>/is',$matcheach,$match2);
		//print_r($match2);
		if(empty($match2[1]))
		{
			//echo 'error: no get categoryname result!';	
		}
		else
		{
			foreach($match2[1] as $key=> $eachcat)
				$rssinfo->cat .=' '.getrealname($eachcat);
		}
		
		//开始写入数据库信息
		//print_r($rssinfo);
		insertonlylink($rssinfo);
	}
}

function getrealname($name)
{
	//echo '----'.$name.'*****';
	$name = preg_replace('/\<\!\[CDATA\[(.*?)\]\]\>/s','$1',trim($name));
	return trim($name);
}
?>
