<?php 
/* 使用示例 */   
//header('Content-Type:text/html;charset= UTF-8'); 
//require("../../config.php");
//require("../../curl.php");
//require("../common.php");
//
//$douban_result = new MovieResult();
//$douban_result->title='致我们终将逝去的青春';
//get_gewara($douban_result);
//print_r($douban_result);

//处理电影名  
function get_gewara(&$resultlast)  
{ 
	//如果有则不需要搜索了
	preg_match('/<4>(.*?)<\/4>/s',$resultlast->ids,$match);
	if(!empty($match[1]))
	{
		return;
	}	
	$name = rawurlencode($resultlast->title);        
	$buffer = get_file_curl('https://www.google.com.hk/search?hl=en&q='.$name.'+site:gewara.com%2Fmovie%2F');
	if(false===$buffer)
	{
		echo $resultlast->title."搜索失败 </br>\n";
		return;
	}
	//echo $buffer;
	preg_match('/href="\/url\?q=http:\/\/www\.gewara\.com\/movie\/(.*?)&amp;/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$resultlast->ids .='<4>'.$match[1].'</4>';
	}
}
?>  