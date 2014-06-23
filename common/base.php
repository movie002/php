<?php
//删除单词  
function delete_word( &$name, $word )  
{  
	$name = str_replace($word,"",$name);  
}  
  
//剪裁单词,若包含，返回TRUE  
function cut_word( &$name, $word)  
{  
	if( strstr($name,$word) )  
	{  
		delete_word($name,$word); 
		return TRUE;      
	}  
	return FALSE;  
}  

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

function dh_file_put_contents($filename, $content) {
	
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

function getupdatebegin($day,$table='link')
{
	//默认设置成120天之前
	//$day = 365;
	$sql="select max(updatetime) from ".$table;
	$res = dh_mysql_query($sql);	
	$updatetime = mysql_fetch_array($res);
	$timenow = strtotime($updatetime[0]);
	$timebegin = $timenow - $day*24*3600;
	$datebegin = date("Y-m-d",$timebegin);
	$datebegin = date("Y-m-d H:i:s",strtotime($datebegin));
	echo $updatetime[0].' -> '.$datebegin."</br>\n";
	return $datebegin;
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
function getrealtime($timebuf)
{
	$timetobe=date("Y-m-d H:i:s",strtotime($timebuf));
	$datenow = date("Y-m-d H:i:s");
	if($timetobe > $datenow)
		return $datenow;
	return $timetobe;
}

function ftrim2($str)
{
    $str = trim($str);
    $str = str_replace('　', ' ', $str); //将全角空格转换为半角空格
    $str = preg_replace('/^\s*|\s*$/is', '', $str); //将开头或结尾的一个或多个半角空格转换为空  
    return $str;
}
?>