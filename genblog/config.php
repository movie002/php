<?php

date_default_timezone_set ('Asia/Shanghai');

//每页显示的记录的个数
$pagecount=10;
//预留几页的空间
//$pagebgen=2;



$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/dhblog/';
$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/dhblog/';
//$DH_home_url= 'http://127.0.0.1/dhblog/';
$DH_home_url= 'http://dhblog.org/';


$DH_src_path= $DH_input_path. 'gen/';
$DH_html_path= $DH_src_path . 'html/';
$DH_output_html_path = $DH_output_path.'html/';
$DH_output_index_path = $DH_output_path.'index/';
$DH_html_url= $DH_home_url.'html/';
$DH_index_url= $DH_home_url.'index/';

$DH_name= '灯火部落';
$DH_name_des= '建站技术分享';
//页面显示的文章列表条目的个数
$DH_page_count_limit = 4;
//分页中能展示的页面数
$DH_page_range = 6;

//index中显示的每个index个数
$index_list_count = 8;

//页面存储的深度
$DH_page_store_deep = 2;
//每个深度的页面个数
$DH_page_store_count = 300;


function setshare($DH_output_content,$js)
{
	global $DH_home_url,$DH_input_path,$DH_html_path;
	$DH_share_output_path = $DH_input_path.'gen/top/';
	$DH_input_html  = $DH_share_output_path . 'meta.html';
	$DH_output_meta = dh_file_get_contents("$DH_input_html");
	$DH_input_html  = $DH_share_output_path . 'side.html';
	$DH_output_side = dh_file_get_contents("$DH_input_html");
	$DH_input_html  = $DH_share_output_path . 'head.html';
	$DH_output_head = dh_file_get_contents("$DH_input_html");	
	$DH_input_html  = $DH_share_output_path . 'foot.html';
	$DH_output_foot = dh_file_get_contents("$DH_input_html");
	
	$DH_output_content = str_replace("%meta%",$DH_output_meta,$DH_output_content);
	$DH_output_content = str_replace("%side%",$DH_output_side,$DH_output_content);
	$DH_output_content = str_replace("%head%",$DH_output_head,$DH_output_content);
	$DH_output_content = str_replace("%foot%",$DH_output_foot,$DH_output_content);		
	
	$DH_input_html  = $DH_html_path.$js;
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output_content = str_replace("%$js%",$DH_js,$DH_output_content);	
	
	$DH_input_html  = $DH_html_path . 'foot.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output_content = str_replace("%footjs%",$DH_js,$DH_output_content);	
	
	return $DH_output_content;
}
?>