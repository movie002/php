<?php

$DH_output_path= '/srv/v/';
$DH_input_path= '/srv/php/';
$DH_home_url= 'http://v.movie002.com';

//$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/x/';
//$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/php/';
//$DH_home_url= 'http://v.x2y4.com/';

$DH_src_path= $DH_input_path. 'genx/';
$DH_html_path= $DH_src_path . 'html/';
$DH_output_html_path = $DH_output_path.'html/';
$DH_output_index_path = $DH_output_path.'index/';
$DH_html_url= $DH_home_url.'html/';
$DH_index_url= $DH_home_url.'index/';
$DH_name= '小二影视网';
$DH_name_des= '小二影视网_影视资源(电影/电视剧/动漫/综艺/纪录片)大全';

function dh_get_title($type,$title)
{
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
