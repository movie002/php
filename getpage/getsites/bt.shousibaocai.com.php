<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/dbaction.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//get_shousibaocai('超级笑星','名侦探柯南/铁甲奇侠/Iron Man',3,'2000-05-05 00:00:00',4);
//mysql_close($conn);


//处理电影名  
function get_shousibaocai($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from shousibaocai:\n";	
	$name = rawurlencode($title);
	$url='http://bt.shousibaocai.com/list/'.$name.'/1';
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
	preg_match_all('/<span class="highlight">(.*?)<\/span>/s',$buffer,$match0);
	//preg_match_all('/创建时间: (.*?)&nbsp;&nbsp;/s',$buffer,$match1);
	preg_match_all('/大小：(.*?[G|M|K]B)/s',$buffer,$match2);
	preg_match_all('/热度：(.*?)速度/s',$buffer,$match3);
	preg_match_all('/速度：<span style=".*?">(.*?)<\/span>/s',$buffer,$match4);	
	//preg_match_all('/<a class="title" href="magnet\:\?xt=urn\:btih\:(.*?)&amp/s',$buffer,$match5);
	preg_match_all('/magnet\:\?xt\=urn\:btih\:(.*?)\&amp/s',$buffer,$match5);
	//<a class="title" href="magnet:?xt=urn:btih:11a610b1b1988b62a66a9c43affb982d7e034077\&amp
	
	//print_r($match0);
	////print_r($match1);
	//print_r($match2);
	//print_r($match3);
	//print_r($match4);
	//print_r($match5);

	if(empty($match0[1]))
		return;
	
	$i = 0;
	foreach($match0[0] as $key=>$each)
	{	
		if($i>4)
			break;
		$i++;
		$title = trim($each);
		echo $title;
		if(!strstr($title,$title))
			continue;
		//$updatetime = date("Y-m-d H:i:s",strtotime($match1[1][$key]));
		$title .='[热度:'.$match3[1][$key].'/速度:'.$match4[1][$key].']('.$match2[1][$key].')';
		$url='magnet:?xt=urn:btih:'.$match5[1][$key];	
		addorupdatelink($pageid,'shousibaocai',$title,$url,'',4,7,7,0,$updatetime,1);
	}
}
?>  