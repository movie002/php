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
include("../share/share.php");
include("../share/static/function.php");

$DH_static_output_path=$DH_output_path.'static/';
$DH_static_url=$DH_home_url. 'static/';
if (!file_exists($DH_static_output_path))  
	mkdir($DH_static_output_path,0777);
$DH_static_input_path  = $DH_input_path . 'share/static/';

dh_gen_static('aboutus');
dh_gen_static('coperation');
dh_gen_static('help');
dh_gen_static('mianze');

?>
