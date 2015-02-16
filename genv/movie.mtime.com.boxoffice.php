<?php
//require("../config.php");
//require("../curl.php");
//require("../common.php");
//require_once("../gen/common.php");
//
//
//header('Content-Type:text/html;charset= UTF-8'); 
//date_default_timezone_set('PRC');
//set_time_limit(9999); 
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//mysql_query("set names utf8;");

//readerdirect();

$url = array('http://movie.mtime.com/boxoffice/#US',
			'http://movie.mtime.com/boxoffice/#CN',
			'http://movie.mtime.com/boxoffice/#HK',
			'http://movie.mtime.com/boxoffice/#TW',
			'http://movie.mtime.com/boxoffice/#JP');

class boxoffice
{
	var $title;
	var $link;
	var $weekmoney;
	var $allmoney;
}			
			
$siteurl='http://movie.mtime.com/boxoffice/';

function readerdirect()
{
	global $siteurl;
	//打开rss地址，并读取，读取失败则中止
	echo $siteurl."开始获取票房纪录:</br>\n";
	$buff = get_file_curl($siteurl);
	//如果失败，就使用就标记失败次数
	if(!$buff)
	{
		echo 'fail to get file !</br>';	
		return;
	}
	//print_r($buff);
	readrssfile($buff);		
}

function readrssfile($buff)
{
	global $DH_input_path,$url;
	print_r($url);
	preg_match_all("/<div class=\"ticket_list pt12\"[^>]*+>([^<]*+(?:(?!<\/?+div)<[^<]*+)*+)<\/div>/i",$buff,$match1);
	//print_r($match1);
	//最新电影资源
	foreach ($match1[0] as $keydiv=>$div)
	{		
		if($keydiv>=4)
		{
			continue;
		}		
		preg_match_all("/<li[^>]*+>([^<]*+(?:(?!<\/?+li)<[^<]*+)*+)<\/li>/i",$div,$match3);
		//print_r($match3);	
		
		$outputPlace = "<ol class=\"list20px\">\n"; 
		$outputPlace .= "\t<li><span class=\"list\">排名</span><span class=\"name\">片名</span><span class=\"week\">周末票房</span><span class=\"allaum\">累计票房</span>\t</li>\n"; 		
		foreach ($match3[1] as $key=>$eachmovie)
		{
			if($key==0||$key>=7)
			{
				continue;
			}
			$outputPlace .= "\t<li>";
			$boxofficeeach = new boxoffice();
			preg_match("/<a href=\"(.*?)\" target=\"_blank\">(.*?)<\/a>/i",$eachmovie,$match4);
			//print_r($match4);
			$boxofficeeach->title = trim($match4[2]);
			delete_word($boxofficeeach->title,'(new)');
			delete_word($boxofficeeach->title,'（new）');
			//找出对于的 title
			$link = get_movie_url($boxofficeeach->title,$match4[1]);
			
			//$boxofficeeach->link = $match4[1];
			
			preg_match("/<span class=\"weekly\">(.*?)<\/span>/i",$eachmovie,$match5);			
			$boxofficeeach->weekmoney = $match5[1];
			
			preg_match("/<span class=\"sum\">(.*?)<\/span>/is",$eachmovie,$match6);				
			$boxofficeeach->allmoney = trim($match6[1]);
			//print_r($boxofficeeach);
			$outputPlace .= '<span class="list">'.$key."</span>";			
			$outputPlace .= '<span class="name"><a href="'.$link.'" target="_blank" >'.$boxofficeeach->title."</a></span>";
			$outputPlace .= '<span class="week">'.$boxofficeeach->weekmoney."</span>";
			$outputPlace .= '<span class="allaum">'.$boxofficeeach->allmoney."</span>"; 			
			$outputPlace .= "\t</li>\n";
		}
		preg_match("/date=\"(.*?)\"/i",$div,$match2);
		//print_r($match2);	
		preg_match("/detailurl=\"(.*?)\"/i",$div,$match3);		
		//print_r($match3);			
		$outputPlace .= "</ol>"; 		
		$outputPlace .= "\t<div style=\"font-size:12px;margin:5px 0\"><span class=\"fl\">".$match2[1]."</span><span class=\"fl\"> <a href=\"".$match3[1]."\" target=\"_blank\" rel=\"nofollow\">票房综述</a></span><span class=\"fr\"><a href=\"$url[$keydiv]\" target=\"_blank\"  rel=\"nofollow\">更多>></a></span> \t</div>\n"; 		

		if (!file_exists($DH_input_path.'top/'))  
		{   
			mkdir($DH_input_path.'top/',0777);
		}
		
		dh_file_put_contents($DH_input_path.'top/'.$keydiv.'.top',$outputPlace);
	}
	return;
}

function get_movie_url($title,$link)
{
	global $conn,$DH_html_url;
	echo "</br>\n".$title.':';
	$sql="select * from page where title = '$title'";
	//echo $sql;
	$recs=dh_mysql_query($sql);
	$id = 0;
	if(mysql_num_rows($recs)>0)
	{
		$row = mysql_fetch_array($recs);
		$result =  $row['mediaid'].'-->'. $row['title'];
		$id = $row['id'];
		echo $result;
	}
	else
	{
		echo '查找 title 失败 :'."</br>\n";
		$sql="select * from page where aka like '%/$title/%'";
		$recs=dh_mysql_query($sql);
		if(mysql_num_rows($recs)>0)	
		{
			$row = mysql_fetch_array($recs);
			$result =  ' 2次查找 '.$row['mediaid'].'-->'. $row['title']."</br>\n";
			$id = $row['id'];
			echo $result;
		}
		else
		{	//最后没有办法，只能插入一条记录
			echo '查找 aka 失败 ';
		}	
	}
	return output_page_path($DH_html_url,$id);
}
?>