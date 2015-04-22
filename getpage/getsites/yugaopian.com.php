<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/base.php");
//require("../../common/dbaction.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//get_yugaopian('何以笙箫默','',1,'2000-05-05 00:00:00',4);
//mysql_close($conn);


//处理电影名  
function get_yugaopian($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	if($type>1)
		return;
	echo " \n begin to get from yugaopian:\n";	
	$name = rawurlencode($title);
	$url='http://www.yugaopian.com/?view=search&keyword='.$name;
	echo $url."\n";	
	$buffer = get_file_curl($url);
	//echo $buffer;

	if(false==$buffer)
	{
		sleep(5);
		$buffer = get_file_curl($url);
		if(false==$buffer)
		{
			echo $title."搜索失败 </br>\n";
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
		$xtitle =$match1[1][$key];
		if(!strstr($title,$xtitle))
			continue;
		$ytitle ="《".$title."》".$xtitle;
		$updatetime = date("Y-m-d H:i:s");
		$url='http://www.yugaopian.com/movie/'.$match0[1][$key];	
        //echo   $ytitle."-->".$url."-->".$updatetime;
		addorupdatelink($pageid,'预告片世界',$ytitle,$url,'',4,3,7,0,0,$updatetime,1);
	}
}
?>  
