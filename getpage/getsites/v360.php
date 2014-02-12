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
//$douban_result->title='超级笑星';
//#$douban_result->aka='名侦探柯南/铁甲奇侠/Iron Man';
//$douban_result->type=3;
//$douban_result->updatetime='2000-05-05 00:00:00';
//get_v360($douban_result);
//print_r($douban_result);
//mysql_close($conn);

//处理电影名  
function get_v360(&$resultlast,$pageid=-1)
{ 
	echo " \n begin to get from v360:\n";	
	$name = rawurlencode($resultlast->title);   
	$buffer = get_file_curl('http://so.v.360.cn/index.php?kw='.$name);
	//echo $buffer;

	if(false==$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
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
		print_r($match2);
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
		if($resultlast->type!=0 && $thistype!=$resultlast->type)
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
		if($title!=$resultlast->title)
		{
			$akas =explode('/',$resultlast->aka);
			//print_r($akas);			
			if (array_search($title,$akas)==false)
				continue;
		}
		update360link($resultlast,'360影视',$url,$title,3,$pageid);
		echo $url."--> v360 -->".$title."\n";
	}
}
?>  