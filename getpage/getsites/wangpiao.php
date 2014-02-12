<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8'); 
//require("../../config.php");
//require("../../curl.php");
//require("../common.php");
//
//$douban_result = new MovieResult();
//$douban_result->title='致我们终将逝去的青春';
//get_wangpiao($douban_result);
//print_r($douban_result);

function get_wangpiao(&$resultlast)  
{ 
	//如果有则不需要搜索了
	preg_match('/<5>(.*?)<\/5>/s',$resultlast->ids,$match);
	if(!empty($match[1]))
	{
		return;
	}
	$name = rawurlencode($resultlast->title);        
	$buffer = get_file_curl('https://www.google.com.hk/search?hl=en&q='.$name.'+site:wangpiao.com%2Fmovie%2F');
	if(false===$buffer)
	{
		echo $eachtitlesearch."搜索失败 </br>\n";
		continue;
	}
	//echo $buffer;
	preg_match('/www\.wangpiao\.com\/Movie\/([0-9]+)\//s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$resultlast->ids .='<5>'.$match[1].'</5>';
	}
}
?>  