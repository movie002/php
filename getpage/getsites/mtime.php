<?php 
/* 使用示例 */   
//header('Content-Type:text/html;charset= UTF-8'); 
//require("../../config.php");
//require("../../curl.php");
//require("../common.php");
//
//$douban_result = new MovieResult();
//$douban_result->title='致我们终将逝去的青春';
//$douban_result->ids='<r3>34(4444)</r3>';
//get_mtime($douban_result);
//print_r($douban_result);

//处理电影名  
function get_mtime(&$resultlast)  
{ 
	$name = rawurlencode($resultlast->title);    
	$buffer = get_file_curl('https://www.google.com.hk/search?hl=en&q='.$name.'+site:mtime.com');
	if(false===$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
		return;
	}
	//echo $buffer;
	preg_match('/href="\/url\?q=http:\/\/movie\.mtime\.com\/(.*?)\//s',$buffer,$match);
	//print_r($match);
	$mtimeid=$match[1];
	
	preg_match('/<3>(.*?)<\/3>/s',$resultlast->ids,$match);
	if(empty($match[1]))
	{
		$resultlast->ids .= '<3>'.$mtimeid.'</3>';	
	}	
	
	$buffer = get_file_curl('http://movie.mtime.com/'.$mtimeid.'/');		
	//echo $buffer;	
	preg_match('/<span property="v:average">(.*?)<\/span>/s',$buffer,$match1);
	preg_match('/<span property="v:votes">(.*?)<\/span>/s',$buffer,$match2);
	if(!empty($match1[1])&&!empty($match2[1]))
	{
		$newr3='<r3>'.$match1[1].'('.$match2[1].')</r3>';
		preg_match('/<r3>(.*?)<\/r3>/s',$resultlast->ids,$match3);
		if(empty($match3[1]))
		{
			$resultlast->ids .=$newr3;
		}
		else
		{
			$resultlast->ids = preg_replace('/<r3>(.*?)<\/r3>/s',$newr3,$resultlast->ids);;
		}
	}
	preg_match('/<strong class="bold">发行公司：<\/strong>		<a target="_blank" href=".*?" class="mr12">(.*?)<\/a>/s',$buffer,$match1);
	if(!empty($match1[1]))
		$resultlast->meta .='<pc>'.$match1[1].'</pc>';

	preg_match('/<em class="fr"><a href="(.*?)">更多(.*?)个<\/a><span class="gt">&gt;&gt;<\/span><\/em>	<h4 class="lh18 fl">预告片<\/h4>/s',$buffer,$match1);
	if(!empty($match1[1]))
	{	
		$resultlast->mtimetrail= '《'.$resultlast->title.'》时光网预告 ('.$match1[2].'部)';
		$resultlast->mtimetrailurl=$match1[1];	
	}
	return true;
}
?>  