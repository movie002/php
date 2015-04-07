<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_author
/// 函数作用：产生静态的最新的文章列表页面
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
set_time_limit(120);

#需要使用的基础函数
include("../../config.php");
include("../config.php");
include("../../common/base.php");
include("../../common/compressJS.class.php");
include("../share.php");

$DH_author_path=$DH_output_path.'/static/';
if (!file_exists($DH_author_path))  
	mkdir($DH_author_path,0777);
$DH_static_input_html  = $DH_input_path . 'static/';

dh_gen_static('aboutus');
dh_gen_static('coperation');

function dh_gen_static($name)
{
	global $DH_home_url,$DH_static_input_path,$DH_author_path,$DH_name;
	$DH_input_html  = $DH_static_input_path .$name.'.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	$DH_output_content = setshare($DH_output_content,'static.js');
	
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
	$DH_output_content = str_replace("%DH_name%",$DH_name,$DH_output_content);	
	$DH_output_content = str_replace("%permalink%",$DH_home_url.'static/'.$name.'.html',$DH_output_content);	
	$DH_output_file = $DH_author_path.$name.'.html';
	dh_file_put_contents($DH_output_file,$DH_output_content);
}

?>
