<?php

date_default_timezone_set ('Asia/Shanghai');

//每页显示的记录的个数
$pagecount=10;
//预留几页的空间
//$pagebgen=2;

$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/www/';
$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/php/';
//$DH_home_url= 'http://127.0.0.1/dhblog/';
$DH_home_url= 'http://127.0.0.1/www/';

$DH_src_path= $DH_input_path. 'genwww/html/';
$DH_html_path= $DH_input_path . 'genv/html/';
$DH_output_html_path = $DH_output_path.'html/';
$DH_output_index_path = $DH_output_path.'index/';
$DH_html_url= $DH_home_url.'html/';
$DH_index_url= $DH_home_url.'index/';

$DH_name= '二手电影网';
$DH_name_des= '影视资源速递';
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
?>