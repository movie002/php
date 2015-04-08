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
include("../config.php");
include("config.php");
include("../common/base.php");
include("../common/compressJS.class.php");
include("share.php");

$DH_author_path=$DH_output_path.'/static/';
if (!file_exists($DH_author_path))  
	mkdir($DH_author_path,0777);
$DH_static_input_html  = $DH_input_path . 'static/';

dh_gen_static('aboutus');
dh_gen_static('coperation');
dh_gen_static('help');
dh_gen_static('opinion');
dh_gen_static('mianze');
dh_gen_static('links');

?>
