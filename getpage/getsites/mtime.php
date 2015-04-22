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
//get_mtime('何以笙箫默','何以笙箫默电影版/You Are My Sunshine',1,'<i>107分钟</i><g>中国大陆</g><p>2015-04-30(中国大陆)</p><l>汉语普通话</l><t>爱情</t>','2015-04-21 15:33:09','<1>26259634</1>',22170);
//mysql_close($conn);


//处理电影名  
function get_mtime($title,$aka,$type,$meta,$updatetime,$ids,$pageid=-1)
{ 
	echo " \n begin to get from mtime:\n";
	$name = rawurlencode($title);    
	$buffer = get_file_curl('http://www.haosou.com/s?q='.$name.'+site%3Amtime.com');
	if(false===$buffer)
	{
		echo $title."搜索失败 </br>\n";
		return;
	}
	//echo $buffer;
	preg_match('/<a href="http:\/\/movie\.mtime\.com\/(.*?)\//s',$buffer,$match);
	//print_r($match);
	$mtimeid=$match[1];
	
	preg_match('/<3>(.*?)<\/3>/s',$ids,$match);
	if(empty($match[1]))
	{
		$ids .= '<3>'.$mtimeid.'</3>';	
	}	
	
	$buffer = get_file_curl('http://movie.mtime.com/'.$mtimeid.'/');		
	//echo $buffer;	
	preg_match('/<span property="v:average">(.*?)<\/span>/s',$buffer,$match1);
	preg_match('/<span property="v:votes">(.*?)<\/span>/s',$buffer,$match2);
	if(!empty($match1[1])&&!empty($match2[1]))
	{
		$newr3='<r3>'.$match1[1].'('.$match2[1].')</r3>';
		preg_match('/<r3>(.*?)<\/r3>/s',$ids,$match3);
		if(empty($match3[1]))
		{
			$ids .=$newr3;
		}
		else
		{
			$ids = preg_replace('/<r3>(.*?)<\/r3>/s',$newr3,$ids);;
		}
	}
	preg_match('/<strong class="bold">发行公司：<\/strong>		<a target="_blank" href=".*?" class="mr12">(.*?)<\/a>/s',$buffer,$match1);
	if(!empty($match1[1]))
	{
		$newr3='<pc>'.$match1[1].'</pc>';
		preg_match('/<pc>(.*?)<\/pc>/s',$meta,$match3);
		if(empty($match3[1]))
			$meta .='<pc>'.$match3[1].'</pc>';
		else
			$meta = preg_replace('/<r3>(.*?)<\/r3>/s',$newr3,$meta);
	}
	preg_match('/<em class="fr"><a href="(.*?)">更多(.*?)个<\/a><span class="gt">&gt;&gt;<\/span><\/em>	<h4 class="lh18 fl">预告片<\/h4>/s',$buffer,$match1);
	if(!empty($match1[1]))
	{
		$mtimetrail= '《'.$title.'》时光网预告 ('.$match1[2].'部)';
		$mtimetrailurl=$match1[1];
		addorupdatelink($pageid,'时光网预告',$mtimetrail,$mtimetrailurl,'',4,7,7,0,0,$updatetime,1);
	}
	//更新page
	$sql="update page set ids='$ids',meta='$meta' where id=$pageid";
	echo $sql;
	$sqlresult=dh_mysql_query($sql);
}
?>  
