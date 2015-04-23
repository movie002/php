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
//get_v2345('超级笑星','名侦探柯南/铁甲奇侠/Iron Man',3,'2000-05-05 00:00:00',4);
//mysql_close($conn);

//处理电影名  
function get_v2345($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from v2345:\n";	
	$name = rawurlencode($title);
	$url='http://so.v.2345.com/search_'.$name;
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
	
	preg_match('/<ul class="ul_picTxt clearfix" id="searchlist">(.*?)<\/ul>/s',$buffer,$match0);	
	//print_r($match0);
	if(empty($match0[1]))
		return;
	
	preg_match_all('/<li>(.*?)<\/li>/s',$match0[1],$match1);	
	//print_r($match1);
	if(empty($match1[1]))
		return;	
		
	foreach($match1[1] as $key=>$each)
	{	
		preg_match('/<a href="([^>]+)" target="_blank" onclick="[^>]+" class="aVideoName" >([^>]+)<\/a>/s',$each,$match2);
		//print_r($match2);
		if(empty($match2[0]))
			continue;
		//判断类型
		$thistype=0;
		if(strstr($match2[1],"http://dianying.2345.com/"))
			$thistype=1;
		if(strstr($match2[1],"http://tv.2345.com/"))
			$thistype=2;
		if(strstr($match2[1],"http://v.2345.com/zongyi/"))
			$thistype=3;
		if(strstr($match2[1],"http://dongman.2345.com/"))
			$thistype=4;
		if($type!=0 && $thistype!=$type)
			continue;		

		$url=$match2[1];
		$title=$match2[2];
		
		//比较名字是否一致
		if($title!=$title)
		{
			$akas =explode('/',$aka);
			//print_r($akas);			
			if (array_search($title,$akas)==false)
				continue;
		}
		
		
		
		preg_match('/<span class="sTxt"><em class="left">(.*?)<\/em><em class="right">(.*?)<\/em><\/span>/s',$each,$match3);
		//print_r($match3);
		if(empty($match3[0]))
		{
			preg_match('/<span class="sTxt"><em class="right">(.*?)<\/em><\/span>/s',$each,$match3);
			//print_r($match3);
			if(empty($match3[0]))
				continue;
			else
				$title .='('.$match3[1].')'; 
		}
		else
			$title .='('.$match3[1].$match3[2].')'; 

		addorupdatelink($pageid,'2345影视',$title,$url,'',4,7,7,0,0,$updatetime,1);
	}
}
?>  
