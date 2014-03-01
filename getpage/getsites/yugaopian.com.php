<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../curl.php");
//require("../../common.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//$douban_result = new MovieResult();
//$douban_result->title='名侦探柯南';
//#$douban_result->aka='名侦探柯南/铁甲奇侠/Iron Man';
//$douban_result->type=1;
//get_yugaopian($douban_result);
//print_r($douban_result);
//mysql_close($conn);


//处理电影名  
function get_yugaopian(&$resultlast,$pageid=-1)
{ 
	if($resultlast->type>1)
		return;
	echo " \n begin to get from yugaopian:\n";	
	$name = rawurlencode($resultlast->title);
	
	$buffer = get_file_curl('http://www.yugaopian.com/?view=search&keyword='.$name);
	//echo $buffer;

	if(false==$buffer)
	{
		sleep(5);
		$buffer = get_file_curl('http://www.yugaopian.com/?view=search&keyword='.$name);
		if(false==$buffer)
		{
			echo $resultlast->title."搜索失败 </br>\n";
			return;
		}
	}
	//判断类型和名字
	$buffer = iconvbuff($buffer);
	//print_r($buffer);
	preg_match_all('/<a href="\/movie\/(.*?)" target="_blank">/s',$buffer,$match0);
	preg_match_all('/data-name="(.*?)"/s',$buffer,$match1);
	
	//print_r($match0);
	//print_r($match1);

	if(empty($match0[1]))
		return;
	
	$i = 0;
	foreach($match1[1] as $key=>$each)
	{	
		if($i>4)
			break;
		$i++;
		$title =$match1[1][$key];
		if(!strstr($title,$resultlast->title))
			continue;
		$updatetime = date("Y-m-d H:i:s");
		$url='http://www.yugaopian.com/movie/'.$match0[1][$key];	
		insertsiteslink($updatetime,$resultlast->mediaid,'预告片世界',$title,$url,2,3,4,0,0,1,$pageid);
		//echo $url."--> shousibaocai -->".$title." ==> ".$url."\n";
	}
}
?>  