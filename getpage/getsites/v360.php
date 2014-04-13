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
	$buffer = get_file_curl('http://so.v.360.cn/index.php?kw='.$name);
	//echo $buffer;

	if(false==$buffer)
	{
		echo $title."搜索失败 </br>\n";
		return;
	}
	//判断类型和名字
	
	preg_match_all('/<div class="le-figure (.*?)<\/div><\/div><\/div>/s',$buffer,$match0);	
	//print_r($match0);
	if(empty($match0[1]))
		return;
	
	foreach($match0[1] as $key=>$each)
	{	
		preg_match('/<h3 class="title"><a href="(.*?)" .*?><b>(.*?)<\/b><\/a><span class="playtype">\[(.*?)\]<\/span><\/h3>/s',$each,$match2);
		//print_r($match2);
		if(empty($match2[0]))
			continue;
			
		$url=$match2[1];
		$title=$match2[2];
		//判断类型
		$thistype=0;
		if(strstr($match2[1],"http://v.360.cn/m/"))
			$thistype=1;
		if(strstr($match2[1],"http://v.360.cn/tv/"))
			$thistype=2;
		if(strstr($match2[1],"http://v.360.cn/ct/"))
			$thistype=4;
		if(strstr($match2[1],"http://v.360.cn/va/"))
			$thistype=3;
		if($type!=0 && $thistype!=$type)
			continue;	
	
		//如果是电影需要判断是否可以立刻播放
		if($thistype==1)
		{
			preg_match('/<span class="btn-icon">立即播放<\/span>/s',$each,$match1);
			//print_r($match1);
			if(empty($match1[0]))
				continue;
		}
		
		//比较名字是否一致
		if($title!=$title)
		{
			$akas =explode('/',$aka);
			//print_r($akas);			
			if (array_search($title,$akas)==false)
				continue;
		}
		addorupdatelink($pageid,'360影视',$title,$url,'',4,7,7,0,$updatetime,1);
	}
}
?>  