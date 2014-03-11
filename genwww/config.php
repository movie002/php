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


$DH_src_path= $DH_input_path. 'genwww/';
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

function output_page_path($basepath,$id) 
{	
	global $DH_page_store_deep,$DH_page_store_count;
	$result=$basepath;
	$useid = $id;
	for($i= $DH_page_store_deep-1;$i>0;$i--)
	{
		$cut = pow($DH_page_store_count,$i);
		$mod = floor($useid/$cut);
		$result .= $mod .'/';
		if (!file_exists($result))  
		{   
			mkdir($result,0777);
		}
		$useid = $useid%$cut;
	}
	$result .=$useid.'.html';
	return $result;	
}

function dh_file_get_contents($filename) 
{
	$fh = fopen($filename, 'rb', false)or die("Can not open file: $filename.\n");
	clearstatcache();
	if ($fsize = @filesize($filename)) {
		$data = fread($fh, $fsize);
	} else {
		$data = '';
		while (!feof($fh)) {
			$data .= fread($fh, 8192);
		}
	}
	fclose($fh);
	return $data;
}

function dh_file_put_contents($filename, $content)
{	
	// Open the file for writing
	$fh = @fopen($filename, 'wb', false)or die("Can not open file: $filename.\n");
	// Write to the file
	$ext=strrchr($filename,'.');
//	if ($ext=='.html')
//		$content = higrid_compress_html($content);
	@fwrite($fh, $content);
	// Close the handle
	@fclose($fh);
}

function compress_html($string)
{
	$pattern = array ( 
	"/> *([^ ]*) *</", //去掉注释标记 
	"/[\s]+/", 
	"/<!--[^!]*-->/", 
	"/\" /", 
	"/ \"/", 
	"'/\*[^*]*\*/'" 
	); 
	$replace = array ( 
	">\\1<", 
	" ", 
	"", 
	"\"", 
	"\"", 
	"" 
	); 
	$string = preg_replace($pattern, $replace, $string);
	$string = str_replace("\r\n", '', $string); //清除换行符 
	$string = str_replace("\n", '', $string); //清除换行符 
	$string = str_replace("\t", '', $string); //清除制表符
	return $string;
}

function higrid_compress_html($higrid_uncompress_html_source ) 
{ 
	$chunks = preg_split( '/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE ); 
	$higrid_uncompress_html_source = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记 
	foreach ( $chunks as $c ) 
	{ 
		if ( strpos( $c, '<pre' ) !== 0 ) 
		{ 
			//[higrid.net] remove new lines & tabs 
			$c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c ); 
			// [higrid.net] remove extra whitespace 
			$c = preg_replace( '/\\s{2,}/', ' ', $c ); 
			// [higrid.net] remove inter-tag whitespace 
			$c = preg_replace( '/>\\s</', '><', $c ); 
			// [higrid.net] remove CSS & JS comments 
			$c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c ); 
		} 
		$higrid_uncompress_html_source .= $c; 
	} 
	return $higrid_uncompress_html_source; 
} 

function iconvbuff($buff)
{
	mb_detect_order('UTF-8,gbk,gb2312,cp950,cp936');
	$encoding = mb_detect_encoding($buff);
	echo $encoding;
	//$buff = mb_convert_encoding($buff, "UTF-8", $encoding);
	$buff = iconv($encoding,"UTF-8//IGNORE", $buff);
	//print_r($buff);
	return $buff;
}

function iconvbuffgbk($buff)
{
	mb_detect_order('UTF-8,gbk,gb2312,cp950,cp936');
	$encoding = mb_detect_encoding($buff);
	//echo $encoding;
	//$buff = mb_convert_encoding($buff, "UTF-8", $encoding);
	$buff = iconv($encoding,"GBK//IGNORE", $buff);
	//print_r($buff);
	return $buff;
}
?>