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
//get_v360('超级笑星','名侦探柯南/铁甲奇侠/Iron Man',3,'2000-05-05 00:00:00',4);
//mysql_close($conn);

//处理电影名  
function get_v360($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from v360:\n";	
	$name = rawurlencode($title); 
	$url='http://so.360kan.com/index.php?kw='.$name;
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

	preg_match_all('/<a href="([^>\"]+)" class="le\-btn le\-btn\-green" data\-logger="ctype\=detail"><span class="btn\-icon">立即播放<\/span><\/a>/s',$buffer,$match0);	
	//print_r($match0);
	if(empty($match0[1]))
		return;
	preg_match_all('/<a href="([^>"]+)" data\-logger="ctype=detail">(.*?)<\/a>/s',$buffer,$match1);	
	//print_r($match1);
	if(empty($match1[1]))
		return;
		
	foreach($match0[1] as $key=>$each)
	{				
		$url=$each;
		//判断类型
		$thistype=0;
		if(strstr($each,"http://www.360kan.com/m/"))
			$thistype=1;
		if(strstr($each,"http://www.360kan.com/tv/"))
			$thistype=2;
		if(strstr($each,"http://www.360kan.com/ct/"))
			$thistype=4;
		if(strstr($each,"http://www.360kan.com/va/"))
			$thistype=3;
		if($type!=0 && $thistype!=$type)
			continue;	
	
		$titlekey = array_search($each,$match1[1]);
		$titlex=$match1[2][$titlekey];
		//处理title
		
		$titlex = str_replace('<b>','',$titlex);
		$titlex = str_replace('</b>','',$titlex);
		//比较名字是否一致
		if($titlex!=$title)
		{
			$akas =explode('/',$aka);
			//print_r($akas);			
			if (array_search($titlex,$akas)==false)
				continue;
		}
		addorupdatelink($pageid,'360影视','《'.$title.'》360影视在线',$url,'',4,7,7,0,0,$updatetime,1);
	}
}
?>  