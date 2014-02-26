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
//get_shousibaocai($douban_result);
//print_r($douban_result);
//mysql_close($conn);


//处理电影名  
function get_shousibaocai(&$resultlast,$pageid=-1)
{ 
	echo " \n begin to get from shousibaocai:\n";	
	$name = rawurlencode($resultlast->title);
	
	$buffer = get_file_curl('http://bt.shousibaocai.com/?s='.$name);
	//echo $buffer;

	if(false==$buffer)
	{
		sleep(5);
		$buffer = get_file_curl('http://bt.shousibaocai.com/?s='.$name);
		if(false==$buffer)
		{
			echo $resultlast->title."搜索失败 </br>\n";
			return;
		}
	}
	//判断类型和名字
	$buffer = iconvbuff($buffer);
	//print_r($buffer);
	preg_match_all('/<li>([^>]+)<span style="color:#888;">/s',$buffer,$match0);
	preg_match_all('/创建时间: (.*?)&nbsp;&nbsp;/s',$buffer,$match1);
	preg_match_all('/大小: (.*?)&nbsp;&nbsp;/s',$buffer,$match2);
	preg_match_all('/热度: (.*?)&nbsp;&nbsp;/s',$buffer,$match3);
	preg_match_all('/速度: <span style=".*?">(.*?)<\/span>/s',$buffer,$match4);	
	preg_match_all('/<a href="magnet:\?xt=urn:btih:(.*?)&dn/s',$buffer,$match5);	
	
//	print_r($match0);
//	print_r($match1);
//	print_r($match2);
//	print_r($match3);
//	print_r($match4);
//	print_r($match5);

	if(empty($match0[1]))
		return;
	
	$i = 0;
	foreach($match1[1] as $key=>$each)
	{	
		if($i>4)
			break;
		$i++;
		$title = trim($match0[1][$key]);
		if(!strstr($title,$resultlast->title))
			continue;
		$updatetime = date("Y-m-d H:i:s",strtotime($match1[1][$key]));
		$title .='[热度:'.$match3[1][$key].'/速度:'.$match4[1][$key].']('.$match2[1][$key].')';
		$url='magnet:?xt=urn:btih:'.$match5[1][$key];	
		insertsiteslink($updatetime,$resultlast->mediaid,'shousibaocai',$title,$url,5,3,4,0,4,1,$pageid);
		//echo $url."--> shousibaocai -->".$title." ==> ".$url."\n";
	}
}
?>  