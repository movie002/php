<?php 
/* 使用示例 */   
//header('Content-Type:text/html;charset= UTF-8'); 
//include("../base.php");
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//$douban_result = new DoubanResult();
//$douban_result->title='超级大坏蛋';
//get_dianying_fm($douban_result);
//print_r($douban_result);

//处理电影名  
function get_dianying_fm(&$result)  
{ 
	//设置默认值
	$name = rawurlencode($result->title);
	
	$buffer = get_file_curl('http://dianying.fm/category/key_'.$name);
	if(false==$buffer)
	{
		echo "搜索失败 </br>\n";
		return;
	}
	//print_r($buffer);
	preg_match('/<div class=\"pull-left x-movie-mediumimg\">(.*?)<\/div>/si',$buffer,$match);
	//print_r($match);
	//判断返回的结果数据
	if(empty($match[1]))
	{
		echo "结果少于一条，退出 </br>\n";
		return;		
	}
	
	$name='';
	$link='';
	preg_match('/alt="(.*?)"/',$match[1],$match2);
	//print_r($match2);
	if(!empty($match2[1]))
	{
		$name=$match2[1] .'(在线视频、磁力链接)';
	}
	
	preg_match('/href="(.*?)"/',$match[1],$match2);
	//print_r($match2);
	if(!empty($match2[1]))
	{
		$link='http://www.dianying.fm/'. $match2[1];
	}	
	$datenow=date("Y-m-d H:i:s");
	$sql="insert into link(mediaid,author,title,link,quality,way,type,updatetime) values ('$result->mediaid','电影FM','$name','$link',2,3,6,'$datenow') ON DUPLICATE KEY UPDATE title='$name' , updatetime='$datenow'";
	$sqlresult=dh_mysql_query($sql);
}
?>  