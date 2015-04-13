<?php

$DH_output_path= '/srv/v/';
$DH_input_path= '/srv/php/';
$DH_home_url= 'http://v.movie002.com/';
$DH_search_input_path="/srv/php/share/";
$DH_search_output_path="/srv/s/";

//$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/v/';
//$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/php/';
//$DH_home_url= 'http://127.0.0.1/v/';
//$DH_search_input_path=$_SERVER['DOCUMENT_ROOT']."/php/share/";
//$DH_search_output_path=$_SERVER['DOCUMENT_ROOT'] . '/s/';


$DH_src_path= $DH_input_path. 'genv/';
$DH_html_path= $DH_src_path . 'html/';
$DH_output_html_path = $DH_output_path.'html/';
$DH_output_index_path = $DH_output_path.'index/';
$DH_html_url= $DH_home_url.'html/';
$DH_index_url= $DH_home_url.'index/';
$DH_name= '电影小二网';
$DH_name_des= '电影大全_电视剧大全_影视大全导航';

function dh_get_title($type,$title)
{
    $cat = '';
	if($type==1)
		$cat =$title.'电影(%pubyear%)下载地址等资源汇总';
	if($type==2)
		$cat =$title.'电视剧全集_在线观看等资源汇总';
	if($type==3)
		$cat =$title.'综艺全集_在线观看等资源汇总';
	if($type==4)
		$cat =$title.'动画(动漫)全集_在线观看等资源汇总';
	return $cat;
}

?>